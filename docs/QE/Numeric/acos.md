# acos

The `acos` function is used to return the arc cosine of a number. It takes a single parameter, `$parameter`, which
represents the number for which you want to find the arc cosine.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::acos($number)->as('acos'),
);
```