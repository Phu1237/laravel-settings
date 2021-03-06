# Laravel Settings
[![Version](https://img.shields.io/packagist/v/phu1237/laravel-settings?style=flat-square)](https://packagist.org/packages/phu1237/laravel-settings)
[![License](https://img.shields.io/packagist/l/phu1237/laravel-settings?style=flat-square)](https://github.com/Phu1237/laravel-settings/blob/master/LICENSE)

Global setting for laravel. Can use in controller, blade, etc.

**Content table**
- [Installation](#installation)
- [Usage](#usage)
  - [Facade](#facade)
  - [Helpers](#helpers)
- [Command](#command)
- [Test](#test)
- [License](#license)

**Supported version**

|Version|Support|
|---|---|
|8.x|Yes|
|7.x|Yes|
|<7.x|Not tested yet|

## Installation
- Require this package with composer:
```bash
composer require phu1237/laravel-settings
```

### Laravel auto-discovery:

Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

### Laravel without auto-discovery:
If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Phu1237\LaravelSettings\SettingsServiceProvider::class,
```

If you want to use the facade to log messages, add this to your facades in app.php:
```php
'Setting' => Phu1237\LaravelSettings\Facades\Setting::class,
```

- Then run migrate to create settings table:
```bash
php artisan migrate
```

- Done. You can use all functions now.

## Usage

**Example setting row:**
![Screenshot](screenshot.png)

You can use both **Facade** and **Helpers** to get or set the setting.

### Facade
```php
// Get
Setting::all();
Setting::get('key');
Setting::value('key');
Setting::meta('key');
Setting::meta('key', 'attribute');
// Set
Setting::set('key', 'value');
Setting::set(['key' => 'value']);
Setting::value('key', 'value');
Setting::value(['key' => 'value']);
Setting::meta('key', 'attribute', 'value');
Setting::meta('key', ['attribute' => 'value']);
// Other
Setting::has('key');
Setting::forget('key');
```

### Helpers

To get field from setting
```php
// Get model of setting
settings('key');
settings()->get('key');
// Get value of setting
settings()->value('key');
// Get meta(s) from setting
settings()->meta('key');
settings()->meta('key', 'attribute');
```

To set field value to setting
```php
// Set value for single key 
settings()->value('key', 'value');
settings()->set('key', 'value');
// Set value for single or multiple key(s)
settings([
    'key1' => 'value1',
    'key2' => 'value2'
]);
settings()->value([
    'key1' => 'value1',
    'key2' => 'value2'
]);
settings()->set([
    'key1' => 'value1',
    'key2' => 'value2'
]);
// Set meta for single attribute
settings()->meta('key', 'attribute', 'value');
// Set meta for single or multiple attribute(s)
settings()->meta('key', [
    'attribute1' => 'value1',
    'attribute2' => 'value2'
]);
```

Other
```php
// Get all settings
settings()->all();
// Check if setting exists or not
settings()->has('key');
// Forget (Destroy) setting
settings()->forget('key');
```

## Command
Publish all available files
```bash
php artisan settings:publish
```
Copy the package config to your local config with the publish command:
```bash
php artisan vendor:publish --provider="Phu1237\LaravelSettings\SettingsServiceProvider" --tag=config
```
Copy the package tests to your local tests with the publish command:
```bash
php artisan vendor:publish --provider="Phu1237\LaravelSettings\SettingsServiceProvider" --tag=tests
```

## Test
First publish package test files with command from [Command](#command)

Then run
```bash
php artisan test
```
or
```bash
vendor/bin/phpunit
```

## License
The Laravel Settings is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
