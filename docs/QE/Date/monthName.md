# monthName

The `monthName` function you provided is similar in purpose to [Carbon](https://carbon.nesbot.com/){:target="_blank"}'s
monthName method. Both are used to retrieve the monthName part for a given date or datetime.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$book = Book::select(
    'id',
    'name',
    QE::monthName(c('published_at'))->as('published_month_name')
)->first();
```