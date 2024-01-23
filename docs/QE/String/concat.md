# concat

The `concat` method, as part of the String namespace, is a static method designed to concatenate two or more expressions
together. It takes a variable number of parameters, representing the strings or expressions to be concatenated.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$author = Author::select(
    'first_name',
    'last_name',
    QE::concat(c('first_name'), ' ', c('last_name'))->as('full_name')
)->first();
```