# greatest

The `greatest` function is designed to determine and return the greatest value from a list of arguments. It takes a
variable number of parameters, representing the values to be compared, and it returns the highest among them.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::greatest($x, $y, $z)->as('greatest'),
);
```