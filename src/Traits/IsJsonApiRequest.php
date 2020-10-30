<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Traits;

trait IsJsonApiRequest
{
    use HasFilterField;
    use HasGroupFields;
    use HasIncludeFields;
    use HasBasePaginationFields;
    use HasSortFields;

    public function jsonApiRules(): array
    {
        return array_merge(
            $this->sortRules(),
            $this->filterRules(),
            $this->groupRules(),
            $this->includeRules(),
            $this->paginationRules()
        );
    }
}
