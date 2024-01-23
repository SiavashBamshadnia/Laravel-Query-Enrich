# substr

The `substr` method, as part of the String namespace, is a static method designed to extract a substring from a given
string starting at a specified position, with an optional length parameter. It takes three parameters: `$string`,
representing the original string from which the substring will be extracted, `$start`, representing the starting
position, and `$length`, representing the optional length of the substring. if the length is null, the method retrieves
characters from the starting position until the end of the string.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::substr($string, $start, $length)->as('result')
);
```