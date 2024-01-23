# subDate

The `subDate` function is designed to subtract a specified time or date interval from a given date and then return the
resulting date. It takes three parameters: `$subject`, representing the original date or datetime, `$value`,
representing the amount to be subtracted, and an optional `$interval` parameter (defaulting to Date\Unit::DAY),
specifying the unit of the value to be subtracted (e.g., days, months).

!!! warning

    While both this function and the [Carbon](https://carbon.nesbot.com/){:target="_blank"} package handle date
    manipulations effectively, there can be subtle discrepancies in their behavior when dealing with month, quarter, or
    year increments or decrements. For example, incrementing December 31st, 2023 by two months using Carbon results in
    March 2nd, 2024, while using SQL logic for the same operation produces February 29th, 2024, as the last day of
    February. Similarly, decrementing January 1st, 2024 by two years using Carbon results in December 31st, 2021, while
    SQL logic generates December 31st, 2022, due to the presence of leap years.

## Example Usage

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::where('published_at', '<', QE::subDate(QE::now(), 7, Unit::DAY));
```