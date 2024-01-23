# Installation

## 1. Require the Package via Composer:

Run the following command in your terminal to add the package to your project:

```bash
composer require sbamtr/laravel-query-enrich
```

## 2. Service Provider:

You may need to add the service provider to your `config/app.php` file:

```php
'providers' => [
    // ...
    sbamtr\LaravelQueryEnrich\ServiceProvider::class,
],
```

# Usage

Now that you have the package installed, you can start using it in your project. You can use the functions directly or
through the `QE` facade if you've set it up.

Here's a simple example:

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

That's it! Your LaravelQueryEnrich library is now installed and ready to use in your Laravel project. If you have any
questions or face any issues, refer to the documentation or feel free to ask for support. Happy coding!