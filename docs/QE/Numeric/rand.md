# rand

The `rand` method, as part of the Numeric namespace, is a static method that can be used to generate and return a random
number. It takes an optional parameter `$seed`, which represents the seed for the random number generator. If no seed is
provided, a pseudo-random number is generated.

!!! warning "Warning PostgreSQL"

    The rand function with seed is not supported on the current PostgreSQL client. We may add it on the next versions.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::radianToDegrees($x)->as('radian_to_degrees'),
);
```