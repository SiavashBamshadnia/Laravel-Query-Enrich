# space

The `space` method, as part of the String namespace, is a static method designed to return a string consisting of a
specified number of space characters. It takes a single parameter, `$parameter`, representing the number of spaces to
generate in the resulting string.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::space($string)->as('result')
);
```