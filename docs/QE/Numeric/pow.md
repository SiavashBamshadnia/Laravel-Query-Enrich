# pow

The `pow` method, as part of the Numeric namespace, is a static method that can be used to calculate and return the
value of a number raised to the power of another number. It takes two parameters: `$x`, representing the base number,
and `$y`, representing the exponent.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$circles = Circle::select(
    'id',
    'radius',
    QE::multiply(QE::pi(), QE:pow(c('radius'), 2))->as('area'),
)->get();
```