<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Traits;

trait HasBasePaginationFields
{
    protected string $paginationField = 'page';

    public function pageKey() : string
    {
        return $this->paginationField;
    }

    public function pageSizeKey() : string
    {
        return $this->pageKey().'.size';
    }

    public function pageNumberKey() : string
    {
        return $this->pageKey().'.number';
    }

    public function page() : array
    {
        return $this->{$this->pageKey()};
    }

    public function paginationRules() : array
    {
        return array_merge(
            [$this->pageNumberKey() => $this->numberRules()],
            [$this->pageSizeKey() => $this->sizeRules()],
        );
    }

    protected function numberRules() : array
    {
        $rules = [
            'nullable',
            'integer',
        ];

        if (! is_null($min = $this->minNumber())) {
            $rules[] = 'min:'.$min;
        }

        return $rules;
    }

    protected function sizeRules() : array
    {
        $rules = [
            'nullable',
            'integer',
        ];

        if (! is_null($min = $this->minSize())) {
            $rules[] = 'min:'.$min;
        }

        if (! is_null($max = $this->maxSize())) {
            $rules[] = 'max:'.$max;
        }

        return $rules;
    }

    protected function maxSize() : ?int
    {
        return config('json-api-request.base_pagination.size.max', null);
    }

    protected function minSize() : ?int
    {
        return config('json-api-request.base_pagination.size.min', null);
    }

    protected function minNumber() : ?int
    {
        return config('json-api-request.base_pagination.number.min', null);
    }
}
