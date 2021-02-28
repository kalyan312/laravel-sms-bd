# Laravel SMS BD


This is a Laravel library to send SMS and switch between multiple SMS Gateways.

## Installation

You can install the package via composer:

``` bash
composer require khbd/laravel-sms-bd
```
The package will register itself automatically.

Then publish the package configuration file

```bash
php artisan vendor:publish --provider=Khbd\LaravelSmsBD\SMSServiceProvider
```
or
```bash
php artisan vendor:publish --provider=Khbd\LaravelSmsBD\SMSServiceProvider  --tag="sms"
```
For store sms log in db run migration
```bash
php artisan migrate
```
## Usage

Check the config file of all variables required, and then

```php
(new SMS())->send('01945602071','Test SMS');
```
or using Facade

```php
SMS::send('01945602071','Test SMS');
```

or using helper

```php
sms()->send('01945602071','Test SMS');
```

## Adding new Gateway

use command 
```bash
php artisan make:gateway MyGateway
```

A class `MyGateway.php` will be generated under `App/Gateways` folder.

The class extends the [SMSInterface]()

Remember to `map` your gateway in the sms config file.

### Changing Gateway

Apart from declaring your default gateway on the sms config or env files, you can also change the gateway you want to use on the fly. e.g: 

```php
SMS::gateway('mygateway')->send('01945602071','Test SMS');
```

### Checking SMS balance

```php
SMS::getBalance();

//or

SMS::gateway('mygateway')->getBalance();

```
### Delivery Reports
```php
sms()->getDeliveryReports(Request $request);

//or

sms()->gateway('mygateway')->getDeliveryReports(Request $request);
```


## .env Config

Currently Default SMS Gateway is [Bangladesh SMS](http://bangladeshsms.com/)

So .env config is following -

BANGLADESH_SMS_BASE_URL = 'http://bangladeshsms.com'
BANGLADESH_SMS_USERNAME = 'username'
BANGLADESH_SMS_API_KEY = 'api_key'
BANGLADESH_SMS_FROM = 'api_provided_number'

SMS_LOG = true  // true = if you want to save sms log in database
SMS_ACTIVATE = true // true = if you want to enable sms sending functionality 


## Contributing

Suggestions, pull requests , bug reporting and code improvements are all welcome. Feel free.

## TODO

Write Tests

## Credits

- [Kalyan Halder](https://github.com/kalyan312)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
