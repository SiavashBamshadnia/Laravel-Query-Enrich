# log

The `log` method, as part of the Numeric namespace, is a static method that can be used to calculate and return the
logarithm of a given number with a specified base. The method takes two parameters: `$number`, representing the number
for which the logarithm is calculated, and `$base`, representing the logarithmic base (defaulting to 2 if not
specified).

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::log($x)->as('log'),
);
```