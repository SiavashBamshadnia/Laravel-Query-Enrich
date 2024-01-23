# if

The purpose of this function is to implement an IF condition for introducing branching in database queries. The function
takes three parameters:

- A condition (of type DBFunction)
- A value to return if the condition is true,
- A value to return if the condition is false.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::select(
    QE::if(QE::condition(c('price'), '>', 100), 'expensive', 'not expensive')->as('price_category')
)->get();
```