<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasFilterField;

class FilterRequestTest extends TestCase
{
    protected ?FormRequest $formClass = null;

    public function setUp() : void
    {
        parent::setUp();

        $this->formClass = new class extends FormRequest {
            use HasFilterField;

            public function rules()
            {
                return $this->filterRules();
            }
        };
    }

    public function test_it_is_ok_with_no_data()
    {
        $this->formRequest(get_class($this->formClass))
            ->assertValidationPassed();
    }

    public function test_it_is_ok_with_null_group()
    {
        $this->formRequest(get_class($this->formClass), [
            'filter' => null,
        ])->assertValidationPassed();
    }

    public function test_it_fails_with_group_wrong_type_int()
    {
        $this->formRequest(get_class($this->formClass), [
            'filter' => 1,
        ])
        ->assertValidationErrors(['filter'])
            ->assertValidationMessages(['The filter must be an array.']);
    }

    public function test_it_fails_with_group_wrong_type_string()
    {
        $this->formRequest(get_class($this->formClass), [
            'filter' => 'wrong_type',
        ])->assertValidationErrors(['filter'])
            ->assertValidationMessages(['The filter must be an array.']);
    }

    public function test_it_has_default_single_rules()
    {
        $this->formRequest(get_class($this->formClass), [
            'filter' => 'wrong_type',
        ])->assertValidationErrors(['filter'])
            ->assertValidationMessages(['The filter must be an array.']);
    }

    public function test_it_can_get_filters()
    {
        $request = (new $this->formClass);

        $request = $request->merge([
            'filter' => [
                'columnNull' => '',
                'columnOne' => 'valueOne',
                'columnTwo' => 'valueTwo,valueThree',
            ],
        ]);

        $this->assertEquals(
            [
                'columnOne' => 'valueOne',
                'columnTwo' => [
                    'valueTwo',
                    'valueThree',
                ],
            ],
            $request->filters()
        );
    }

    public function test_it_can_has_filter()
    {
        $request = (new $this->formClass);

        $request = $request->merge([
            'filter' => [
                'columnOne' => 'valueOne',
                'columnTwo' => 'valueTwo,valueThree',
            ],
        ]);

        $this->assertTrue(
            $request->hasFilter('columnOne')
        );

        $this->assertTrue(
            $request->hasFilter('columnTwo')
        );
    }
}
