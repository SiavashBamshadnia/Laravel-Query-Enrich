# padLeft

The `padLeft` method, as part of the String namespace, is a static method designed to left-pad a given string with
another string to a specified length. It takes three parameters: `$string`, representing the original string to be
padded, `$length`, representing the desired total length of the padded string, and `$pad`, representing the string used
for padding (defaulting to a space character if not specified).

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::padLeft('James', 10, '-=')->as('result')
);
```