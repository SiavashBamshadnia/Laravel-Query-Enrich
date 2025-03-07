# Laravel Query Enrich

[![Latest Stable Version](http://poser.pugx.org/sbamtr/laravel-query-enrich/v)](https://packagist.org/packages/sbamtr/laravel-query-enrich)
[![unittests](https://github.com/SiavashBamshadnia/Laravel-Query-Enrich/actions/workflows/unittests.yml/badge.svg)](https://github.com/SiavashBamshadnia/Laravel-Query-Enrich/actions/workflows/unittests.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/siavashbamshadnia/laravel-query-enrich/badge)](https://www.codefactor.io/repository/github/siavashbamshadnia/laravel-query-enrich)
[![License](http://poser.pugx.org/sbamtr/laravel-query-enrich/license)](https://github.com/SiavashBamshadnia/Laravel-Query-Enrich/blob/main/LICENSE)

_Supports Laravel 8, 9, 10, 11, 12_

## Introduction

Laravel Query Enrich makes it easy to create complex database queries in Laravel without having to write complicated SQL
code. It simplifies the way developers interact with databases, making it more straightforward to build and read queries
in Laravel applications. With Query Enrich, you can achieve advanced database operations without the need for extensive
SQL knowledge.

## Benefits of Using Laravel Query Enrich:

### No Hard Query Code

You don't have to struggle with complicated SQL. Laravel Query Enrich makes your queries simpler.

### Ensures Cross-Database Compatibility

If you decide to switch the database engine, Laravel Query Enrich eliminates the need for manual code refactoring. It
handles the difference between different database engines and makes things switch smoothly, all without needing
programmers to do anything!

### Easy-to-Read Code

Your code becomes cleaner and easier to understand. Laravel Query Enrich makes interacting with databases in your
Laravel applications a breeze.

## Example Usage

Look at the following examples. They are way cooler with the Laravel Query Enrich. Aren't they? :)

### Categorize books into different price categories (using case when)

#### With Laravel Query Enrich

```php
$books = Book::select(
    'id',
    'name',
    QE::case()
        ->when(c('price'), '>', 100)->then('expensive')
        ->when(QE::condition(50, '<', c('price')), QE::condition(c('price'), '<=', 100))->then('moderate')
        ->else('affordable')
        ->as('price_category')
)->get();
```

#### Without Laravel Query Enrich

```php
$books = Book::select(
    'id',
    'name',
    DB::raw('
        CASE
            WHEN price > 100 THEN "expensive"
            WHEN price BETWEEN 50 AND 100 THEN "moderate"
            ELSE "affordable"
        END AS price_category
    ')
)->get();
```

#### Raw SQL

```mysql
select case
           when `price` > 100 then 'expensive'
           when (50 < `price` and `price` <= 100) then 'moderate'
           else 'affordable' end as `price_category`
from `books`
```

### Fetch orders placed in the last 7 days

#### With Laravel Query Enrich

```php
$recentOrders = DB::table('orders')
    ->where(c('created_at'), '>=', QE::subDate(QE::now(), 7, Unit::DAY))
    ->get();
```

#### Without Laravel Query Enrich

```php
$recentOrders = DB::table('orders')
    ->whereRaw('created_at >= NOW() - INTERVAL ? DAY', 7)
    ->get();
```

#### Raw Query

```mysql
SELECT *
FROM `orders`
WHERE `created_at` >= NOW() - INTERVAL 7 DAY;
```

### Get average monthly price for oil and gas (using avg function)

#### With Laravel Query Enrich

```php
$monthlyPrices = DB::table('prices')
    ->select(
        QE::avg(c('oil'))->as('oil'),
        QE::avg(c('gas'))->as('gas'),
        'month'
    )
    ->groupBy('month')
    ->get();
```

#### Without Laravel Query Enrich

```php
$monthlyPrices = DB::table('prices')
    ->select(DB::raw('avg(`oil`) as `oil`, avg(`gas`) as `gas`, `month`'))
    ->groupBy('month')
    ->get();
```

#### Raw Query

```mysql
select avg(`oil`) as `oil`, avg(`gas`) as `gas`, `month`
from `prices`
group by `month`
```

### Fetch authors and check if they have any books (using exists query)

#### With Laravel Query Enrich

```php
$authors = DB::table('authors')->select(
    'id',
    'first_name',
    'last_name',
    QE::exists(
        Db::table('books')->where('books.author_id', c('authors.id'))
    )->as('has_book')
)->orderBy(
    'authors.id'
)->get();
```

#### Without Laravel Query Enrich

```php
$authors = DB::table('authors')
->select(
    'id',
    'first_name',
    'last_name',
    DB::raw('exists(select * from `books` where `books`.`author_id` = `authors`.`id`) as `has_book`'))
->orderBy(
    'authors.id',
)
->get();
```

#### Raw Query

```mysql
select `id`,
       `first_name`,
       `last_name`,
       exists(select * from `books` where `books`.`author_id` = `authors`.`id`) as `result`
from `authors`
order by `authors`.`id` asc
```

### Getting full name on database level (using concatws)

#### With Laravel Query Enrich

```php
$authors = Author::select(
    'first_name',
    'last_name',
    QE::concatWS(' ', c('first_name'), c('last_name'))->as('result')
)->get();
```

#### Without Laravel Query Enrich

```php
$author = Author::select(
    'first_name',
    'last_name',
    DB::raw("concat_ws(' ', `first_name`, `last_name`) as `result`")
)->first();
```

#### Raw Query

```mysql
select `first_name`, `last_name`, concat_ws(' ', `first_name`, `last_name`) as `result`
from `authors`
```

## Installation and documentation

The complete documentation is available here:

https://laravel-query-enrich.readthedocs.io/

## License

The MIT License (MIT). Please see the [License file](LICENSE) for more information.

----

Written with ♥ by Siavash Bamshadnia.

Please support me by staring this repository.
