<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Traits;

use Illuminate\Support\Str;

trait HasGroupFields
{
    protected string $groupField = 'group';

    public function groupKey() : string
    {
        return $this->groupField;
    }

    public function groups() : array
    {
        return array_filter(explode(',', (string) $this->{$this->groupKey()}));
    }

    public function hasGroup(string $name) : bool
    {
        return collect($this->groups())->contains(
            fn ($item) => Str::of($item)->contains($name)
        );
    }

    public function groupRules() : array
    {
        $groupKey = $this->groupKey();

        return [
            $groupKey => [
                'nullable',
                'string',
            ],
        ];
    }
}
