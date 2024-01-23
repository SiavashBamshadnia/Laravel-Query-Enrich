# left

The `left` method, as part of the String namespace, is a static method designed to extract a specified number of
characters from the left side of a string. It takes two parameters: `$string`, representing the original string from
which characters will be extracted, and `$numberOfChars`, representing the number of characters to extract.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    'id',
    'title',
    QE::concat(QE::left(c('description'), 10), '...')->as('short_description')
)->get();
```