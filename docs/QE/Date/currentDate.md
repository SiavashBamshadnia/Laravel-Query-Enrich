# currentDate

The `currentDate` function is a method that retrieves the current date. This function does not require any parameters
and simply returns the current date when called.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;

Book::where('id', 1)->update(['published_at'=>QE::currentDate()]);
```