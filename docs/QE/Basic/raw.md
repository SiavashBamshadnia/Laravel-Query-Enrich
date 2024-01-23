# Raw

The `raw` function represents a raw SQL expression. It contains two arguments:

`$sql`, which is the raw SQL string, and `$bindings`, which holds an array of parameter bindings.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

$queryResult = DB::selectOne(
    'select ' . QE::raw('?*?', [2, 2])->as('four'),
);
```
