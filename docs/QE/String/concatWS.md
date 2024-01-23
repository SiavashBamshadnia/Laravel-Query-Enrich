# concatWS

The `concatWS` method, as part of the String namespace, is a static method designed to concatenate two or more
expressions together with a specified separator. It takes at least two parameters: `$separator`, representing the string
that will separate the concatenated expressions, and the rest of the parameters, representing the strings or expressions
to be concatenated.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$author = Author::select(
    'first_name',
    'last_name',
    QE::concatWS(' ', c('first_name'), c('last_name'))->as('full_name')
)->first();
```