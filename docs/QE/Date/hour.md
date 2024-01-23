# hour

The `hour` function you provided is similar in purpose to [Carbon](https://carbon.nesbot.com/){:target="_blank"}'s hour
method. Both are used to retrieve the hour part for a given time or datetime.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$book = Book::select(
    'id',
    'name',
    QE::hour(c('published_at'))->as('published_hour')
)->first();
```