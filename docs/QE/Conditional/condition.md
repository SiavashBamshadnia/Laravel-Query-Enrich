# condition

The `condition` function is used to create a condition by specifying two parameters (`$parameter1` and `$parameter2`)
and an optional operator (`$operator`). It allows you to construct conditions for comparisons. The first parameter,
`$parameter1`, represents the value or expression on the left side of the condition. The optional `$operator` parameter
defines the comparison operation (e.g., equals, greater than, less than, default value is equals), and the `$parameter2`
parameter represents the value or expression on the right side of the condition.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    'id',
    'name',
    QE::condition(c('price'), '>', 100)->as('expensive'),
    QE::condition(c('price'), 0)->as('free')
)->get();
```