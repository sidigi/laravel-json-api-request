<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Traits;

trait HasFilterField
{
    protected string $filterField = 'filter';
    protected $defaultRules = ['nullable', 'array'];

    public function filterKey() : string
    {
        return $this->filterField;
    }

    public function filters(string $key = null)
    {
        $filters = $this->strToArray(
            $this->{$this->filterKey()}
        );

        if ($key) {
            return $filters[$key] ?? null;
        }

        return $filters;
    }

    public function hasFilter(string $name) : bool
    {
        return array_key_exists($name, $this->filters());
    }

    protected function filterRules() : array
    {
        return array_replace_recursive(
            [$this->filterKey() => $this->defaultRules],
            $this->getValueFilterRules(),
            $this->getEachValueFilterRules(),
        );
    }

    protected function eachValueFilterRules() : array
    {
        return [];
    }

    protected function valueFilterRules() : array
    {
        return [];
    }

    protected function strToArray($data) : array
    {
        return collect($data)
            ->filter()
            ->mapWithKeys(function (?string $item, string $key) {
                $values = explode(',', $item);

                return [
                    $key => count($values) === 1 ? $values[0] : $values,
                ];
            })
            ->toArray();
    }

    private function getValueFilterRules() : array
    {
        return collect($this->valueFilterRules())->mapWithKeys(
            fn ($item, $key) => [
                $this->filterKey().'.'.$key => array_merge($this->defaultRules, $item),
            ]
        )
        ->toArray();
    }

    private function getEachValueFilterRules() : array
    {
        $filterRules = collect();

        collect($this->eachValueFilterRules())
            ->each(function ($item, $key) use (&$filterRules) {
                $withFilterKey = $this->filterKey().'.'.$key;

                if ($this->isForArray($key)) {
                    $filterRules[$withFilterKey] = $this->defaultRules;
                    $filterRules[$withFilterKey.'.*'] = $item;
                } else {
                    $filterRules[$withFilterKey] = array_merge(['nullable'], $item);
                }
            })->toArray();

        return $filterRules->toArray();
    }

    private function isForArray(string $name) : bool
    {
        return $this->filters($name) && is_array($this->filters($name));
    }
}
