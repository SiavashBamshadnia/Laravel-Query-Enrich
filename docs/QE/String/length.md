# length

The `length` method, part of the String namespace, is a static method designed to retrieve the length of a given string.
When called, it takes a single parameter, `$parameter`, which represents the string for which the length needs to be
determined. This function is particularly useful when you need to ascertain the number of characters in a string

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    'id',
    'title',
    QE::length(c('title'))->as('title_length')
)->get();
```