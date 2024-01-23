# degreesToRadian

The `degreesToRadian` function is designed to convert a given degree value into radians. It takes a single parameter,
`$parameter`, which represents the degree value to be converted.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::degreesToRadian($x)->as('degrees_to_radian'),
);
```