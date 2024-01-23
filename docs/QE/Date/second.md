# second

The `second` function you provided is similar in purpose to [Carbon](https://carbon.nesbot.com/){:target="_blank"}'s
second method. Both are used to retrieve the second part for a given time or datetime.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$book = Book::select(
    'id',
    'name',
    QE::second(c('published_at'))->as('published_second') // That's a weird example. But I couldn't come up with a better example! :D
)->first();
```