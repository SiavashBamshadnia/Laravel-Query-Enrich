# rtrim

The `rtrim` method, as part of the String namespace, is a static method designed to remove trailing spaces from the end
of a given string. It takes a single parameter, `$parameter`, representing the string from which trailing spaces will be
removed.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    'id',
    QE::rtrim(c('title'))->as('title')
)->get();
```