<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Traits;

use Illuminate\Support\Str;

trait HasIncludeFields
{
    protected string $includeField = 'include';

    public function includeKey() : string
    {
        return $this->includeField;
    }

    public function includes() : array
    {
        return explode(',', (string) $this->{$this->includeKey()});
    }

    public function hasInclude(string $name) : bool
    {
        return collect($this->includes())
            ->contains(
                fn ($item) => Str::of($item)->contains($name)
            );
    }

    public function includeRules() : array
    {
        $includeKey = $this->includeKey();

        return [
            $includeKey => [
                'nullable',
                'string',
            ],
        ];
    }
}
