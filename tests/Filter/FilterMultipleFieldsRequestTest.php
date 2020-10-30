<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasFilterField;

class FilterMultipleFieldsRequestTest extends TestCase
{
    protected ?FormRequest $formClass = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->formClass = new class() extends FormRequest {
            use HasFilterField;

            public function rules()
            {
                return $this->filterRules();
            }

            public function eachValueFilterRules()
            {
                return [
                    'columnOne' => ['string'],
                    'columnTwo' => ['string', 'uuid'],
                ];
            }
        };
    }

    public function test_it_has_single_filters_rules()
    {
        $request = (new $this->formClass());

        $request = $request->merge([
            'filter' => [
                'columnOne' => 'valueOne',
                'columnTwo' => 'valueTwo,valueThree',
            ],
        ]);

        $this->assertEquals($request->rules(), [
            'filter' => [
                'nullable',
                'array',
            ],
            'filter.columnOne' => [
                'nullable',
                'string',
            ],
            'filter.columnTwo' => [
                'nullable',
                'array',
            ],
            'filter.columnTwo.*' => [
                'string',
                'uuid',
            ],
        ]);
    }
}
