# sign

The `sign` method, as part of the Numeric namespace, is a static method that can be used to determine and return the
sign of a given number. The method takes a single parameter, `$parameter`, representing the number for which you want to
find the sign.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::sign($x)->as('sign'),
);
```