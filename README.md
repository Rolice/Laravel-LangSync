# Laravel-LangSync
Language Extractor and Synchroniser

This package is developed for Laravel 4.2. Larvel 5.0+ support is comming soon.

## Installation
You can easily install the package in your Laravel project by simply executing the following command:

`composer require rolice/lang-sync`

After the installation you have to add the service provider in app config:

```php
'Rolice\LangSync\LangSyncServiceProvider'
```

for PHP 5.6+ you can add the following:

```php
Rolice\LangSync\LangSyncServiceProvider::class
```

## Usage
The usage is done through artisan command. This is done through command line like:

`php artisan lang:extract`

You can easily call it programmatically from your PHP code like, in case command line is not accessible:

```php
Artisan::call('lang:extract')
```
