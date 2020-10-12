<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasFilterField;

class FilterFieldsRequestTest extends TestCase
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

            public function eachValueFilterRules()
            {
                return [
                    'columnOne' => ['multipleRule'],
                ];
            }

            public function valueFilterRules()
            {
                return [
                    'columnOne' => ['singleRule'],
                    'columnTwo' => ['singleRule'],
                ];
            }
        };
    }

    public function test_it_has_filters_rules()
    {
        $request = (new $this->formClass);

        $request = $request->merge([
            'filter' => [
                'columnOne' => 'valueOne,valueTwo',
                'columnTwo' => 'valueOne,valueTwo',
            ],
        ]);

        $this->assertEquals($request->rules(), [
            'filter' => [
                'nullable',
                'array',
            ],
            'filter.columnOne' => [
                'nullable',
                'array',
                'singleRule',
            ],
            'filter.columnTwo' => [
                'nullable',
                'array',
                'singleRule',
            ],
            'filter.columnOne.*' => [
                'multipleRule',
            ],
        ]);
    }
}
