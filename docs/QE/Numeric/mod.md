# mod

The `mod` method, as part of the Numeric namespace, is a static method that can be used to calculate and return the
remainder when a number is divided by another number. It takes two parameters: `$x`, representing the numerator,
and `$y`, representing the denominator.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::mod($x, $y)->as('mod'),
);
```