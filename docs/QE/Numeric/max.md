# max

The `max` method, as part of the Numeric namespace, is a static method that can be used to determine and return the
maximum value from a set of numeric values. The method takes a parameter, allowing you to provide the column.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    QE::max(c('price'))->as('most_expensive')
)->groupBy(
    'author_id'
)->get();
```