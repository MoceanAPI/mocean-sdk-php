MoceanAPI Client Library for PHP 
============================
[![Latest Stable Version](https://img.shields.io/packagist/v/mocean/client.svg)](https://packagist.org/packages/mocean/client)
[![Build Status](https://img.shields.io/travis/com/MoceanAPI/mocean-sdk-php.svg)](https://travis-ci.com/MoceanAPI/mocean-sdk-php)
[![codecov](https://img.shields.io/codecov/c/github/MoceanAPI/mocean-sdk-php.svg)](https://codecov.io/gh/MoceanAPI/mocean-sdk-php)
[![codacy](https://img.shields.io/codacy/grade/7a1e94f1c1ea40fdbfa362ecbbc4b2f3.svg)](https://app.codacy.com/project/MoceanAPI/mocean-sdk-php/dashboard)
[![StyleCI](https://github.styleci.io/repos/138724921/shield?branch=master)](https://github.styleci.io/repos/138724921)
[![License](https://img.shields.io/packagist/l/mocean/client.svg)](https://packagist.org/packages/mocean/client)
[![Total Downloads](https://img.shields.io/packagist/dt/mocean/client.svg)](https://packagist.org/packages/mocean/client)

*This library requires a minimum PHP version of 5.5*

This is the PHP client library for use Mocean's API. To use this, you'll need a Mocean account. Sign up [for free at 
moceanapi.com][signup].

 * [Installation](#installation)
 * [Usage](#usage)
 * [Example](#example)

## Installation

To use the client library you'll need to have [created a Mocean account][signup]. 

To install the PHP client library using Composer.

```bash
composer require mocean/client
```

## Usage

If you're using composer, make sure the autoloader is included in your project's bootstrap file:

```php
require_once "vendor/autoload.php";
```
    
Create a client with your API key and secret:

```php
use Mocean\Client;
use Mocean\Client\Credentials\Basic;

$mocean = new Client(new Basic('API_KEY_HERE','API_SECRET_HERE'));
```

## Example

To use [Mocean's SMS API][doc_sms] to send an SMS message, call the `$mocean->message()->send();` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][doc_sms].

```php
$res = $mocean->message()->send([
    'mocean-to' => '60123456789',
    'mocean-from' => 'MOCEAN',
    'mocean-text' => 'Hello World'
]);

echo $res;
```

### Responses

For your convenient, the API response data can be accessed either using php `object` style or `array` style

```php
echo $res;            //show full response string
echo $res['status'];  //show response status, '0' in this case
echo $res->status;    //same as above
```

## Documentation

Kindly visit [MoceanApi Docs][doc_main] for more usage
    
## License

This library is released under the [MIT License][license]

[signup]: https://dashboard.moceanapi.com/register?medium=github&campaign=sdk-php
[doc_main]: https://moceanapi.com/docs/?php
[doc_sms]: https://moceanapi.com/docs/?php#send-sms
[license]: LICENSE
