#

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sidigi/laravel-json-api-request.svg?style=flat-square)](https://packagist.org/packages/sidigi/laravel-json-api-request)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sidigi/laravel-json-api-request/run-tests?label=tests)](https://github.com/sidigi/laravel-json-api-request/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/sidigi/laravel-json-api-request.svg?style=flat-square)](https://packagist.org/packages/sidigi/laravel-json-api-request)

Provide some JSON API validation stuff JSON API for laravel Form requests.

## Installation

You can install the package via composer:

```bash
composer require sidigi/laravel-json-api-request
```

## Usage

### Controller

```php
    public function index(UserIndexRequest $request)
    {
        $users = User::all();

        return response()->json($users);
    }
```

### Form Request

```php
use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Traits;

class UserIndexRequest extends FormRequest
{
    use IsJsonApiRequest;

    public function rules()
    {
        return $this->jsonApiRules()
    }

    //filter value ?filter[id]=1
    public function valueFilterRules()
    {
        return [
            'id' => 'exists:users,id'
        ]
    }

    //filter value ?filter[id]=1,2,3
    public function eachValueFilterRules()
    {
        return [
            'id' => 'integer'
        ]
    }
}
```

```php
use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Traits;

class UserIndexRequest extends FormRequest
{
    use HasFilterField,
        HasGroupFields,
        HasIncludeFields,
        HasBasePaginationFields,
        HasSortFields;

    public function rules()
    {
        return array_merge(
            $this->sortRules(), // HasSortFields
            $this->filterRules(), //HasFilterField
            $this->groupRules(), //HasGroupFields
            $this->includeRules(), //HasIncludeFields
            $this->paginationRules() //HasBasePaginationFields
        );
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Sidigi](https://github.com/Sidigi)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
