# dayOfWeek

The `dayOfWeek` function is similar in purpose to [Carbon](https://carbon.nesbot.com/){:target="_blank"}'s dayOfWeek
method. Both are used to determine the weekday index for a given date or datetime.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$book = Book::select(
    'id',
    'name',
    QE::dayOfWeek(c('published_at'))->as('published_weekday')
)->first();
```