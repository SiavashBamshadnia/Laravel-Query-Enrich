# now

The `now` function is a method that retrieves the current timestamp. This function does not require any parameters
and simply returns the current date when called.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

Book::where('id', 1)->update(['published_at'=>QE::now()]);
```