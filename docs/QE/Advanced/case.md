# case

The case expression is a powerful tool that allows you to handle multiple conditions in your code, similar to an
if-then-else statement in standard programming languages.

## Example Usage

### Basic Usage

In this example, we add a column named `price_category` to the query response.  
Books with a price greater than `100` are marked as `expensive`, while all others are marked as `affordable`.

```php
use sbamtr\LaravelQueryEnrich\QE;

$books = Book::query()
    ->select(
        QE::case()
            ->when(QE::c('price'), '>', 100)->then('expensive')
            ->else('affordable')
        ->as('price_category')
    )
    ->get();
```

### Multiple cases

In this example, we add a `price_category` column with multiple conditions:  
- Books priced above `200` are marked as `too_expensive`.  
- Books priced above `100` (but `< 200`) are marked as `expensive`.  
- All other books are marked as `affordable`.  

```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::query()
    ->select(
        QE::case()
            ->when(c('price'), '>=', 200)->then('too_expensive')
            ->when(c('price'), '>', 100)->then('expensive')
            ->else('affordable')
        ->as('price_category')
    )
    ->get();
```

### Multiple conditions

In this example, we add a `price_category` column with more detailed conditions:  
- Books with a price greater than `100` are marked as `expensive`.  
- Books with a price between `50` and `100` (inclusive) are marked as `moderate`.  
- All other books are marked as `affordable`.  
```php
use sbamtr\LaravelQueryEnrich\QE;
use function sbamtr\LaravelQueryEnrich\c;

$books = Book::query()
    ->select(
        QE::case()
            ->when(QE::c('price'), '>', 100)->then('expensive')

            ->when(
                QE::condition(50, '<', QE::c('price')),
                QE::condition(QE::c('price'), '<=', 100)
            )->then('moderate')

            ->else('affordable')
        ->as('price_category')
    )
    ->get();
```

### Advanced usage
In this example, we create a report of user registrations grouped by day and user type.  

- We use the [`date`](../Date/date.md) function to format the `created_at` field as `Y-m-d`.  
- A `case` expression marks users as **real** if `legal_id` is `null`, or **legal** otherwise.  
- The [`count`](../Numeric/count.md) function counts the users in each group.  

This way, you get a daily breakdown showing how many real and legal users registered in the selected time range.


```php
use sbamtr\LaravelQueryEnrich\QE;
use Carbon\Carbon;

$from = Carbon::today()->startOfWeek();
$until = Carbon::today();

$statics = User::query()
    ->select(
        /**
         * Convert `created_at` to `Y-m-d` format
         */
        QE::date(QE::c('created_at'))->as('date'),

        /**
         * Detect type of the user based on `legal_id` nullish.
         */
        QE::case()
            ->when(QE::isNull(QE::c('legal_id')))->then('real')
            ->else('legal')
            ->as('type'),

        /**
         * Count each type values.
         */
        QE::count()->as('count')
    )
    ->whereBetween('created_at', [
        $from->startOfDay(), $until->endOfDay()
    ])
    ->groupBy('date', 'type')
    ->get()
```
Which results to:
```json
[
    {
        "date": "2025-02-01",
        "type": "real",
        "count": 8
    },
    {
        "date": "2025-02-02",
        "type": "real",
        "count": 7
    },
    {
        "date": "2025-02-03",
        "type": "real",
        "count": 7
    },
    {
        "date": "2025-02-03",
        "type": "legal",
        "count": 2
    },
    {
        "date": "2025-02-04",
        "type": "real",
        "count": 9
    },
    {
        "date": "2025-02-05",
        "type": "real",
        "count": 5
    },
    {
        "date": "2025-02-05",
        "type": "legal",
        "count": 1
    }
]
```