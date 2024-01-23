# exp

The `exp` function is used to calculate and return the value of Euler's number (e) raised to the power of a specified
number, represented by the parameter `$parameter`.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::exp($x)->as('exp'),
);
```