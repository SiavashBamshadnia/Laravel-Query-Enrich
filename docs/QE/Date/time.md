# time

The `time` function is used to extract the time part from a datetime expression. It takes a single parameter,
`$parameter`, which represents the original datetime expression.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$book = Book::select(
    'id',
    'name',
    QE::time(c('published_at'))->as('published_time')
)->first();
```