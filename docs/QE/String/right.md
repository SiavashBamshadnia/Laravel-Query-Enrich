# right

The `right` method, as part of the String namespace, is a static method designed to extract a specified number of
characters from the right side of a string. It takes two parameters: `$string`, representing the original string from
which characters will be extracted, and `$numberOfChars`, representing the number of characters to extract.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::right($string)->as('result')
);
```