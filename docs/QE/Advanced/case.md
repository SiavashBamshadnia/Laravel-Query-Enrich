# case

The case expression is a powerful tool that allows you to handle multiple conditions in your code, similar to an
if-then-else statement in standard programming languages.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    QE::case()
        ->when(c('price'), '>', 100)->then('expensive')
        ->when(QE::condition(50, '<', c('price')), QE::condition(c('price'), '<=', 100))->then('moderate')
        ->else('affordable')
        ->as('price_category')
)->get();
```
