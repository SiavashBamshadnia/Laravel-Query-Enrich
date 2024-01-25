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
    sbamtr\LaravelQueryEnrich\QueryEnrichServiceProvider::class,
],
```

That's it! The Laravel Query Enrich library is now installed and ready to use in your Laravel project. If you have any
questions or face any issues, refer to the documentation or feel free to ask for support. Happy coding!