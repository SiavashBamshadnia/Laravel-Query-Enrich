# radianToDegrees

The `radianToDegrees` method, as part of the Numeric namespace, is a static method that can be used to convert a value
from radians to degrees. It takes a single parameter, `$parameter`, representing the value in radians that you want to
convert.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::radianToDegrees($x)->as('radian_to_degrees'),
);
```