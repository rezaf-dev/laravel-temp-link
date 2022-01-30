# Laravel Temporary Link

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rezaf-dev/laravel-temp-link.svg?style=flat-square)](https://packagist.org/packages/rezaf-dev/laravel-temp-link)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/rezaf-dev/laravel-temp-link/run-tests?label=tests)](https://github.com/rezaf-dev/laravel-temp-link/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/rezaf-dev/laravel-temp-link/Check%20&%20fix%20styling?label=code%20style)](https://github.com/rezaf-dev/laravel-temp-link/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rezaf-dev/laravel-temp-link.svg?style=flat-square)](https://packagist.org/packages/rezaf-dev/laravel-temp-link)

If you have a private file and want to give access to it for a short period, this package helps you create a temporary link to this file. It doesn't need a database and works just by symlinks. Users can directly access a private file with a direct link that is randomly generated. A scheduled task will delete expired links.

## Installation

You can install the package via composer:

```bash
composer require rezaf-dev/laravel-temp-link
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-temp-link-config"
```

This is the contents of the published config file:

```php
return [
    'temp_link_path' => '/temp',
    'disk' => 'public',
    'scheduler' => true,
];
```

## Usage

You can use TempLink Facade to generate a new link:
```php
user \RezafDev\LaravelTempLink\Facades;
echo TempLink::generateTempLink('path/to/private/file.txt', 3600); 
// http://127.0.0.1:8000/storage/16435/325/61f6430b18189.txt
```

You need to set [laravel scheduler](https://laravel.com/docs/8.x/scheduling#running-the-scheduler) to run regularly to remove expired links. Please note that if you run a cron job every 10 minutes, you shouldn't generate links with an expiration time less than 10 minutes (You can, but it doesn't work).

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

You can also run this command manually to remove the expired links:
```
php artisan templink:delete
```
If you want to schedule this command yourself, you can set "scheduler" config to false.

To make the deletion more efficient, you should not generate links with less than 100 seconds expiration time.

## Testing

```bash
./vendor/bin/phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
