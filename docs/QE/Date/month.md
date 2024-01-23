# month

The `month` function you provided is similar in purpose to [Carbon](https://carbon.nesbot.com/){:target="_blank"}'s
month method. Both are used to retrieve the month part for a given time or datetime.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$book = Book::select(
    'id',
    'name',
    QE::month(c('published_at'))->as('published_month')
)->first();
```