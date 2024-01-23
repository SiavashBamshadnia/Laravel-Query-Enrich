# exists

The `exists` function is designed to check if a subquery, provided as
a [`QueryBuilder`](https://laravel.com/docs/queries){:target="_blank"}
or [`EloquentBuilder`](https://laravel.com/docs/eloquent){:target="_blank"} parameter named `$query`, has any results.
It's particularly useful when you want to verify whether a certain condition or set of criteria within a
subquery yields any records. The function returns true if the subquery has results, indicating that data exists based on
the specified conditions. Conversely, it returns false if the subquery does not return any results. This function is
handy for conditional logic where you need to determine the existence of data in a subquery before proceeding with
further actions.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$queryResult = Author::select(
    'id',
    'first_name',
    'last_name',
    QE::exists(
        Book::where('books.author_id', c('authors.id'))
    )->as('has_book')
)->get();
```