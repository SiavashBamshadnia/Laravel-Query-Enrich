# ltrim

The `ltrim` method, as part of the String namespace, is a static method designed to remove leading spaces from the
beginning of a given string. It takes a single parameter, `$parameter`, representing the string from which leading
spaces will be removed.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    'id',
    QE::ltrim(c('title'))->as('title')
)->get();
```