# coalesce

The `coalesce` function is designed to pick the first non-null value from a list of parameters. It takes a variable
number of parameters, and it evaluates each parameter in order. The function returns the first non-null value it
encounters. This is handy when you have multiple values and want to use the first one that has a meaningful or non-null
value. It's a useful tool for handling cases where you might have fallback values or a list of options, and you want to
prioritize the first non-null option.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$book = Book::select(
    QE::coalesce(c('description'), '----')->as('description')
)->first();
```