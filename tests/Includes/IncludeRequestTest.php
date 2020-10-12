<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Includes;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasIncludeFields;

class IncludeRequestTest extends TestCase
{
    protected ?FormRequest $formClass = null;

    public function setUp() : void
    {
        parent::setUp();

        $this->formClass = new class extends FormRequest {
            use HasIncludeFields;

            public function rules()
            {
                return $this->includeRules();
            }
        };
    }

    public function test_it_is_ok_with_no_data()
    {
        $this->formRequest(get_class($this->formClass))
            ->assertValidationPassed();
    }

    public function test_it_is_ok_with_null_include()
    {
        $this->formRequest(get_class($this->formClass), [
            'include' => null,
        ])->assertValidationPassed();
    }

    public function test_it_fails_with_include_wrong_type()
    {
        $this->formRequest(get_class($this->formClass), [
            'include' => 1,
        ])->assertValidationErrors(['include'])
            ->assertValidationMessages(['The include must be a string.']);
    }

    public function test_it_can_get_includes()
    {
        $request = (new $this->formClass);

        $groupData = 'fieldOne,fieldTwo.fieldThree';

        $request = $request->merge([
            'include' => $groupData,
        ]);

        $this->assertEquals(
            explode(',', $groupData),
            $request->includes()
        );
    }

    public function test_it_can_has_include()
    {
        $request = (new $this->formClass);

        $includeData = 'fieldOne,fieldTwo.fieldThree';

        $request = $request->merge([
            'include' => $includeData,
        ]);

        $this->assertTrue(
            $request->hasInclude('fieldOne')
        );

        $this->assertTrue(
            $request->hasInclude('fieldTwo')
        );

        $this->assertTrue(
            $request->hasInclude('fieldThree')
        );

        $this->assertTrue(
            $request->hasInclude('fieldTwo.fieldThree')
        );
    }
}
