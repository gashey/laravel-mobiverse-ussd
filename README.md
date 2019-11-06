# Mobiverse USSD plugin for Laravel
[![Latest Release on GitHub][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]


## About

This is a package to help you integrate the Mobiverse USSD service into your Laravel application. Users will be able to access your application on USSD with codes like <strong>*1234#</strong> from their phones. 

This package uses code from [https://github.com/jowusu837/hubtel-ussd-plugin-laravel](https://github.com/jowusu837/hubtel-ussd-plugin-laravel) created by Victor James Owusu.



## Installation

Require the `gashey/laravel-mobiverse-ussd` package in your `composer.json` and update your dependencies:
```sh
$ composer require gashey/laravel-mobiverse-ussd
```

Add the LaravelMobiverseUssd\UssdServiceProvider to your `providers` array:
```php
Gashey\LaravelMobiverseUssd\ServiceProvider::class,
```

## Usage

Your application should now have an endpoint for USSD access. You can use the [Mobiverse USSD Simulator](https://apps.mobivs.com/USSDSIM/) to test your application. Supply your application url as: http://your-application.com/ussd

## Customization
A simple USSD flow comes with this plugin so you can immediately test to see if your setup is working. 
You can create custom USSD flows by creating `Activities`. 
Start by creating a new folder in your `app` directory called `USSD`. This is where you will store all your USSD related logic.

A USSD activity is just a `php` class that extends the `UssdActivity` class. An example of an activity class is below:

```php
namespace App\Ussd\Activities;

use App\Ussd\Activities\MenuSelection;
use Gashey\LaravelMobiverseUssd\Lib\UssdActivity;
use Gashey\LaravelMobiverseUssd\Lib\UssdResponse;

class HomeActivity extends UssdActivity
{
    public function run() {
        $this->response->Type = UssdResponse::RELEASE;
        $this->response->Message = 'Ussd is working!';
        return $this;
    }
    
    public function next() {
        return MenuSelection::class;
    }

}
```
An activity class must implement 2 methods: `run()` and `next()`. 

The `run()` method is the main entry point for the activity and must always return `$this`. 

The `next()` must return a reference to the next activity to be executed. You can do this by simply returning a string with the full namespace of the next activity class or you can use the `::class` approach to have php resolve it for you.

You have access to the current request `$this->request`, the response to be sent `$this->response`, and the current USSD session `$this->session` from within the activity. 

The request and response properties exposes all the properties of a USSD request and response respectively. 

The session property is an array. Note that the session is implemented on top of your existing Laravel cache. It allows you to persist state throughout your USSD session. You can store a value in the session like so: `$this->session['name'] = 'Jane Deer'`, and retrieve it elsewhere like so: `$name = $this->session['name']`. 

Once you have created your activiy files, you need to set your entry point activity in the config file as shown in the next section.

## Configuration

The defaults are set in `config/ussd.php`. Publish the config using this command:
```sh
$ php artisan vendor:publish --provider="Gashey\LaravelMobiverseUssd\UssdServiceProvider"
```

    
```php
return [
    "home" => \App\Ussd\Activities\HomeActivity::class
];
```
    
## License

Released under the MIT License, see [LICENSE](LICENSE).

[ico-version]: https://img.shields.io/github/release/gashey/laravel-mobiverse-ussd.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/gashey/laravel-mobiverse-ussd.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/gashey/laravel-mobiverse-ussd.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/gashey/laravel-mobiverse-ussd.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/gashey/laravel-mobiverse-ussd
[link-scrutinizer]: https://scrutinizer-ci.com/g/gashey/laravel-mobiverse-ussd/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/gashey/laravel-mobiverse-ussd
[link-downloads]: https://packagist.org/packages/gashey/laravel-mobiverse-ussd
[link-author]: https://github.com/gashey
[link-contributors]: ../../contributors
