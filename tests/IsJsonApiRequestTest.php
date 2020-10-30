<?php

namespace Sidigi\LaravelJsonApiRequest\Tests;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Traits\IsJsonApiRequest;

class IsJsonApiRequestTest extends TestCase
{
    protected ?FormRequest $formClass = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->formClass = new class() extends FormRequest {
            use IsJsonApiRequest;

            public function rules()
            {
                return $this->jsonApiRules();
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
                ];
            }
        };
    }

    public function test_it_can_has_json_api_rules()
    {
        $request = (new $this->formClass());

        $request = $request->merge([
            'filter' => [
                'columnOne' => 'valueOne, valueTwo',
            ],
        ]);

        $rules = $request->rules();

        $this->assertArrayHasKey('sort', $rules);
        $this->assertArrayHasKey('filter', $rules);
        $this->assertArrayHasKey('filter.columnOne', $rules);
        $this->assertArrayHasKey('filter.columnOne.*', $rules);
        $this->assertArrayHasKey('group', $rules);
        $this->assertArrayHasKey('include', $rules);
        $this->assertArrayHasKey('page.number', $rules);
        $this->assertArrayHasKey('page.size', $rules);
    }
}
