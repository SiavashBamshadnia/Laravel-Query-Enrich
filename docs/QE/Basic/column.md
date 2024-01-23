# Column (c)

This function represents a database column and handles the conversion of its name into a SQL-friendly format. It manages
the surrounding characters used in SQL queries, which may differ depending on the database system being used.

You can use this function as `QE:c` and `c`.

## Example Usage

```php
use function sbamtr\LaravelQueryEnrich\c;

$author = Author::select(
    c('first_name')->as('name'),
    'email'
)->first();
```

Or you can do this:

```php
use sbamtr\LaravelQueryEnrich\QE;

$author = Author::select(
    QE::c('first_name')->as('name'),
    'email'
)->first();
```
