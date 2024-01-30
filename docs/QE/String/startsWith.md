# startsWith

The `startsWith` function checks if a provided string starts with a specified substring. To use it, you pass two
parameters: `$haystack` (the full string) and `$needle` (the substring you're looking for). The method then returns a
Boolean, letting you know if the string starts with the specified substring.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$people = People::select(
    'id',
    'first_name',
    'last_name',
    QE::startsWith(c('first_name'), 'A')->as('is_first_name_starting_with_a')
)->get();
```

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$people = People::whereRaw(
    QE::startsWith(c('first_name'), 'Walt')
)->get();
```
