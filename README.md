# Laravel Model Settings

Add settings feature to Eloquent models in Laravel 5.


## Background

This has been developed to simplify adding "settings" feature to any eloquent model on your laravel project. 

Settings WILL be stored in `json` format/object! Is `json` the best way? Well it's your call! pros: super flexible, you can add/remove settings without code alteration (apart from `app/config/model-settings.php` see below). cons: expensive when querying/searching for certain `key => value` in `settings` per model.

`settings` will be casted into Laravel `Collection` for maximum functionality and usage.

## Installation
To install the package via Composer:

```shell
$ composer require yarob/laravel-model-settings
```
Then, update `config/app.php` by adding an entry for the service provider.

```php
'providers' => [
    // ...
    Yarob\LaravelModelSettings\ServiceProvider::class,
];
```
Finally, via terminal, publish the default configuration file:

```shell
php artisan vendor:publish --provider="Yarob\LaravelModelSettings\ServiceProvider"
```
## Updating your Eloquent Models

Your models should use the `hasSettings` trait.
You must also add `settings` to your `fillable` array as shown in the example below

```php
use Yarob\LaravelModelSettings\HasSettings;

class User extends Model
{
    use hasSettings;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'settings'
    ];

}
```

## Migration
Your model MUST have column named `settings` in the database to store the settings values.

You can add this manually via a migration on the intended model ` $table->json('settings')->nullable(); `. 
The column should be big enough to accommodate all settings after json encoded.

## Usage

Better demonstrated in example

```php
$user = App\User::first();

$user->settings()->save(array(
		'address' => 'London',
		'phone_number' => '0123456789'
	    ));
	    
print_r($user->settings);
```
## Configuration

Configuration was made to be as flexible as possible. You can add the allowed settings keys per Model basis.

Global configuration can be set in the `app/config/model-settings.php` file. By default, `phone_number` and `address` settings keys have been added to `User` model. But feel free to change that.

If a configuration isn't set, then the package defaults from 
`vendor/yarob/laravel-model-settings/resources/config/model-settings.php` are used. 
Here is an example configuration:

```php
return [
    'User' => [
    		'phone_number',
    		'address',
    	],
];
```

Pay attention that Model name in `model-settings.php` is case sensitive! so if you have a `foo` Model, then

```php
return [
    'foo' => [
    		'key1',
    		'key2',
    		...
    	],
    	...
];
```

## Copyright and License

[laravel-model-settings](https://github.com/EazyServer/laravel-model-settings)
was written by [Yarob Al-Taay](https://twitter.com/TheEpicVoyage) and is released under the 
[MIT License](LICENSE.md).

Copyright (c) 2017 Yarob Al-Taay