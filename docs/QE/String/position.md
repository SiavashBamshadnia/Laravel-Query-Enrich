# position

The `position` method, as part of the String namespace, is a static method designed to find the position of a specified
substring within a given string. It takes two parameters: `$subString`, representing the substring to search for,
and `$string`, representing the original string.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::position('World', 'Hello, World!')->as('position')
);
```