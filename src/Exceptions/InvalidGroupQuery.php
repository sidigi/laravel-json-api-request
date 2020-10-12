<?php

declare(strict_types=1);

namespace Sidigi\LaravelJsonApiRequest\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidGroupQuery extends HttpException
{
    /** @var \Illuminate\Support\Collection */
    public $unknownGroups;

    /** @var \Illuminate\Support\Collection */
    public $allowedGroups;

    public function __construct(Collection $unknownGroups, Collection $allowedGroups)
    {
        $this->unknownGroups = $unknownGroups;
        $this->allowedGroups = $allowedGroups;

        $unknownGroups = $this->unknownGroups->implode(', ');
        $allowedGroups = $this->allowedGroups->implode(', ');
        $message = "Requested group(s) `{$unknownGroups}` are not allowed. Allowed group(s) are `{$allowedGroups}`.";

        parent::__construct(Response::HTTP_BAD_REQUEST, $message);
    }

    public static function groupsNotAllowed(Collection $unknownGroups, Collection $allowedGroups)
    {
        return new static(...func_get_args());
    }
}
