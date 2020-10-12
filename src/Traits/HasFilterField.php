<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Traits;

use Illuminate\Support\Str;

trait HasFilterField
{
    protected string $filterField = 'filter';
    protected $defaultMultipleRules = ['nullable', 'array'];

    public function filterKey()
    {
        return $this->filterField;
    }

    public function filters() : array
    {
        return explode(',', $this->{$this->filterKey()});
    }

    /**
     * Merge default rules with rules, which need to be array
     * It need if we have in query "filter[id]=1,2,3" - it is array
     * and rules need to be array.
     *
     * @return array
     */
    protected function filterRules() : array
    {
        $filterRules = collect([
            $this->filterKey => $this->defaultMultipleRules,
        ]);

        collect($this->filterMultipleRules())
            ->each(function ($item, $key) use (&$filterRules) {
                if ($this->isForArray($key)) {
                    $filterRules[$key] = $this->defaultMultipleRules;
                    $filterRules[$key.'.*'] = $item;
                } else {
                    $filterRules[$key] = array_merge(['nullable'], $item);
                }
            });

        return array_merge_recursive(
            $filterRules->toArray(),
            $this->filterSingleRules()
        );
    }

    /**
     * Replace filter values if it need check multiple values.
     *
     * filter.id => filter.id = nullable, array
     *              filter.id.* = rules, which was set
     *
     * @return void
     */
    protected function prepareForValidation() : void
    {
        if (! is_array($this->filter)) {
            return;
        }

        $this->merge([
            $this->filterKey => $this->strToArray($this->filter),
        ]);
    }

    protected function filterMultipleRules() : array
    {
        return [];
    }

    protected function filterSingleRules() : array
    {
        return [];
    }

    /**
     * Make string to array if it has "," in it.
     *
     * 1,2,3,4,5 => [1,2,3,4,5]
     * 1 => 1
     *
     * @param array $data
     * @return array
     */
    protected function strToArray(array $data) : array
    {
        return collect($data)->mapWithKeys(function (?string $item, string $key) {
            $values = explode(',', $item);

            return [
                $key => count($values) === 1 ? $values[0] : $values,
            ];
        })->toArray();
    }

    /**
     * Check if it has value in input request and it is an array.
     *
     * @param string $name
     * @return bool
     */
    protected function isForArray(string $name) : bool
    {
        $key = Str::replaceFirst("{$this->filterKey}.", '', $name);

        return isset($this->filter[$key]) && is_array($this->filter[$key]);
    }
}
