<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasFilterField;

class FilterSingleFieldsRequestTest extends TestCase
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

            public function valueFilterRules()
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
        $request = (new $this->formClass);

        $this->assertEquals($request->rules(), [
            'filter' => [
                'nullable',
                'array',
            ],
            'filter.columnOne' => [
                'nullable',
                'array',
                'string',
            ],
            'filter.columnTwo' => [
                'nullable',
                'array',
                'string',
                'uuid',
            ],
        ]);
    }
}
