# endsWith

The `endsWith` function checks if a provided string ends with a specified substring. To use it, you pass two
parameters: `$haystack` (the full string) and `$needle` (the substring you're looking for). The method then returns a
Boolean, letting you know if the string ends with the specified substring.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$people = People::select(
    'id',
    'first_name',
    'last_name',
    QE::endsWith(c('first_name'), 'Junior')->as('is_first_name_ending_with_junior'))
)->get();
```

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$people = People::whereRaw(
    QE::endsWith(c('first_name'), 'Junior')
)->get();
```
