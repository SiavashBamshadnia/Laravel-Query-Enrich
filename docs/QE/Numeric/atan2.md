# atan2

The `atan2` function is used to return the arc tangent of two numbers, `$y` and `$x`. It calculates the angle whose
tangent is the quotient of the two specified numbers.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::atan2($y, $x)->as('atan2'),
);
```