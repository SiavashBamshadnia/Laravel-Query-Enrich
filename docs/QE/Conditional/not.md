# not

The `not` method is used to negate a condition with the logical NOT operator. It takes one parameter, representing the
condition to be negated. The resulting condition is true when the original condition is false, and vice versa.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    QE::not(c('is_published'))->as('is_not_published'),
)->get();
```