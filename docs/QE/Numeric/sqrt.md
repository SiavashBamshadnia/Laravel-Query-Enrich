# sqrt

The `sqrt` method, as part of the Numeric namespace, is a static method that can be used to calculate and return the
square root of a given number. It takes a single parameter, `$parameter`, representing the number for which you want to
find the square root.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::sqrt($x)->as('sqrt'),
);
```