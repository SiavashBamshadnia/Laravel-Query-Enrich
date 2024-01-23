# tan

The `tan` method, as part of the Numeric namespace, is a static method designed to calculate and return the tangent of a
given number. It takes a single parameter, `$parameter`, representing the angle in radians for which you want to find
the tangent.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::tan($x)->as('tan'),
);
```