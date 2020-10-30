<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Sort;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasSortFields;

class SortRequestTest extends TestCase
{
    protected ?FormRequest $formClass = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->formClass = new class() extends FormRequest {
            use HasSortFields;

            public function rules()
            {
                return $this->sortRules();
            }
        };
    }

    public function test_it_is_ok_with_no_data()
    {
        $this->formRequest(get_class($this->formClass))
            ->assertValidationPassed();
    }

    public function test_it_is_ok_with_null_sort()
    {
        $this->formRequest(get_class($this->formClass), [
            'sort' => null,
        ])->assertValidationPassed();
    }

    public function test_it_fails_with_sort_wrong_type()
    {
        $this->formRequest(get_class($this->formClass), [
            'sort' => 1,
        ])->assertValidationErrors(['sort'])
            ->assertValidationMessages(['The sort must be a string.']);
    }

    public function test_it_can_get_sorts()
    {
        $request = (new $this->formClass());

        $sortData = 'fieldOne,fieldTwo.fieldThree';

        $request = $request->merge([
            'sort' => $sortData,
        ]);

        $this->assertEquals(
            explode(',', $sortData),
            $request->sorts()
        );
    }

    public function test_it_can_has_include()
    {
        $request = (new $this->formClass());

        $sortData = 'fieldOne,fieldTwo.fieldThree';

        $request = $request->merge([
            'sort' => $sortData,
        ]);

        $this->assertTrue(
            $request->hasSort('fieldOne')
        );

        $this->assertTrue(
            $request->hasSort('fieldTwo')
        );

        $this->assertTrue(
            $request->hasSort('fieldThree')
        );

        $this->assertTrue(
            $request->hasSort('fieldTwo.fieldThree')
        );
    }
}
