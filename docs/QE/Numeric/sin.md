# sine

The `sin` method, as part of the Numeric namespace, is a static method that can be used to calculate and return the sine
of a given number. It takes a single parameter, `$parameter`, representing the angle in radians for which you want to
find the sine.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::sin($x)->as('sin'),
);
```