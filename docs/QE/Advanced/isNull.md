# isNull

The `isNull` function checks if a given value is empty. It takes a single parameter, and evaluates whether it is
considered empty. If the value is empty, the function returns true; otherwise, it returns false. This function is useful
for validating whether a variable or value holds any data or if it's essentially an empty or null value. It can be
employed in conditional statements or validation processes to ensure that a particular variable has meaningful content.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    'id',
    'name',
    QE::isNull(c('description'))->as('is_description_null')
)->get();
```

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = DB::table('books')->whereRaw(
    QE::isNull(c('description'))
)->get();
```
