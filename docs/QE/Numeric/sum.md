# sum

The `sum` method, as part of the Numeric namespace, is a static method designed to calculate and return the sum of a set
of numeric values. It takes a variable number of parameters, representing the values to be added together.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$orders = OrderItem::select(
    'id',
    'name',
    'price',
    QE::sum(c('price'))->as('total_price')
)->groupBy(
    'order_id',
)->get();
```