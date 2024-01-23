# reverse

The `reverse` method, as part of the String namespace, is a static method designed to reverse the order of characters in
a given string. It takes a single parameter, `$parameter`, representing the string to be reversed.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::reverse($string)->as('result')
);
```