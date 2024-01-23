# repeat

The `repeat` method, as part of the String namespace, is a static method designed to repeat a given string a specified
number of times. It takes two parameters: `$parameter`, representing the string to be repeated, and `$number`,
representing the number of times the string should be repeated.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::repeat($string, $number)->as('repeat')
);
```