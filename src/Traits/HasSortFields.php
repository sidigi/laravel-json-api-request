<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Traits;

use Illuminate\Support\Str;

trait HasSortFields
{
    protected string $sortField = 'sort';

    public function sortKey() : string
    {
        return $this->sortField;
    }

    public function sorts() : array
    {
        return explode(',', $this->{$this->sortKey()});
    }

    public function hasSort(string $name) : bool
    {
        return collect($this->sorts())
            ->contains(
                fn ($item) => Str::of($item)->contains($name)
            );
    }

    public function sortRules() : array
    {
        $includeKey = $this->sortKey();

        return [
            $includeKey => [
                'nullable',
                'string',
            ],
        ];
    }
}
