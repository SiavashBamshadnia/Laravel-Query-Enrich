# avg

The `avg` function is designed to calculate and return the average value of a given column or expression. It takes a
single parameter, `$parameter`, representing the column or expression for which the average needs to be computed. This
function is particularly useful for statistical calculations, providing a convenient way to determine the mean value of
a dataset.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    QE::avg(c('price'))->as('average_price')
)->groupBy(
    'author_id'
)->get();
```