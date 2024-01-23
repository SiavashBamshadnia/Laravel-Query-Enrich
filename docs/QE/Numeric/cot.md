# cot

The `cot` function is used to calculate and return the cotangent of a given number, represented by the
parameter `$parameter`.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::cot($x)->as('cot'),
);
```