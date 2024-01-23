# divide

The `divide` function is designed to divide the first numeric parameter by subsequent numeric parameters. It takes a
variable number of parameters, and it performs the division operation by dividing the first parameter by each of the
following numeric parameters.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$books = Book::select(
    'id',
    'name',
    'price',
    QE::divide(c('discount'), c('price'))->as('discount_ratio')
)->get();
```