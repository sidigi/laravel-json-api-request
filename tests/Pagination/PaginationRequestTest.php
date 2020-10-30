<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Pagination;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasBasePaginationFields;

class PaginationRequestTest extends TestCase
{
    protected ?FormRequest $formClass = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->formClass = new class() extends FormRequest {
            use HasBasePaginationFields;

            public function rules()
            {
                return $this->paginationRules();
            }
        };
    }

    public function test_it_is_ok_with_no_data()
    {
        $this->formRequest(get_class($this->formClass))
            ->assertValidationPassed();
    }

    public function test_it_is_ok_with_null_number()
    {
        $this->formRequest(get_class($this->formClass), [
            'page' => [
                'number' => null,
            ],
        ])->assertValidationPassed();
    }

    public function test_it_is_ok_with_null_size()
    {
        $this->formRequest(get_class($this->formClass), [
            'page' => [
                'size' => null,
            ],
        ])->assertValidationPassed();
    }

    public function test_it_fails_with_number_wrong_type()
    {
        $this->formRequest(get_class($this->formClass), [
            'page' => [
                'number' => 'wrong_type',
            ],
        ])->assertValidationErrors(['page.number'])
            ->assertValidationMessages(['The page.number must be an integer.']);
    }

    public function test_it_fails_with_size_wrong_type()
    {
        $this->formRequest(get_class($this->formClass), [
            'page' => [
                'size' => 'wrong_type',
            ],
        ])->assertValidationErrors(['page.size'])
            ->assertValidationMessages(['The page.size must be an integer.']);
    }

    public function test_it_fails_with_number_less_then_min()
    {
        $minNumber = 1;

        config()->set('json-api-request.base_pagination.number.min', $minNumber);

        $this->formRequest(get_class($this->formClass), [
            'page' => [
                'number' => $minNumber - 1,
            ],
        ])->assertValidationErrors(['page.number'])
            ->assertValidationMessages([sprintf(
                'The page.number must be at least %d.',
                $minNumber
            )]);
    }

    public function test_it_fails_with_size_less_then_min()
    {
        $minSize = 1;

        config()->set('json-api-request.base_pagination.size.min', $minSize);

        $this->formRequest(get_class($this->formClass), [
            'page' => [
                'size' => $minSize - 1,
            ],
        ])->assertValidationErrors(['page.size'])
            ->assertValidationMessages([sprintf(
                'The page.size must be at least %d.',
                $minSize
            )]);
    }

    public function test_it_fails_with_size_more_then_max()
    {
        $maxSize = 1;

        config()->set('json-api-request.base_pagination.size.max', $maxSize);

        $this->formRequest(get_class($this->formClass), [
            'page' => [
                'size' => $maxSize + 1,
            ],
        ])->assertValidationErrors(['page.size'])
            ->assertValidationMessages([sprintf(
                'The page.size may not be greater than %d.',
                $maxSize
            )]);
    }

    public function test_it_can_get_pages()
    {
        $request = (new $this->formClass());

        $pageData = [
            'size'   => 100,
            'number' => 1,
        ];

        $request = $request->merge([
            'page' => $pageData,
        ]);

        $this->assertEquals($pageData, $request->page());
    }
}
