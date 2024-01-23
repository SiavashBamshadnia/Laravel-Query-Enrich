# and

The `and` function is used to combine multiple conditions using the logical AND operator. It takes a variable number of
parameters, representing individual conditions. The function then combines these conditions using the logical AND
operator, meaning that all specified conditions must be true for the combined condition to be true.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$authors = Author::select(
    'id',
    'name',
    QE::and(c('is_famous'), c('is_rich'))->as('is_rockstar'),
)->get();
```