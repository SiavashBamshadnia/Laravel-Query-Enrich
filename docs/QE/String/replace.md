# replace

The `replace` method, part of the String namespace, is a static method designed to replace occurrences of a specified
substring with a new string in a given string. It takes three parameters: `$string` represents the original string,
`$substring` is the portion of the string you want to replace, and `$newString` is the replacement string.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::repeat($string, $substring, $newString)->as('replace')
);
```