# ln

The `ln` method, as part of the Numeric namespace, is a static method that can be used to calculate and return the
natural logarithm of a given number. This method is associated with the logarithmic function, where the base of the
logarithm is the mathematical constant "e" (Euler's number, approximately 2.71828).

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::ln($x)->as('ln'),
);
```