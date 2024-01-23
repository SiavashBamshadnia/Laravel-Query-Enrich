# asin

The `asin` function is used to return the arc sine of a number. It takes a single parameter, `$parameter`, which
represents the number for which you want to find the arc sine.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::asin($number)->as('asin'),
);
```