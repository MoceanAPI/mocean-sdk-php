MoceanAPI Client Library for PHP 
============================

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
$token = new Mocean\Client\Credentials\Basic(
    'API_KEY_HERE', 
    'API_SECRET_HERE'
);
$mocean = new Mocean\Client($token);
```

## Example

To use [Mocean's SMS API][doc_sms] to send an SMS message, call the `$mocean->message()->send();` method.

The API can be called directly, using a simple array of parameters, the keys match the [parameters of the API][doc_sms].

```php
$res = $mocean->message()->send([
    'mocean-to' => '60123456789',
    'mocean-from' => 'MOCEAN',
    'mocean-text' => 'Hello World',
    'mocean-resp-format' => 'json'
]);

echo $res;
```
    
## License

This library is released under the [MIT License][license]

[signup]: https://dashboard.moceanapi.com/register?medium=github&campaign=sdk-php
[doc_sms]: https://docs.moceanapi.com/?php#send-sms
[doc_inbound]: https://docs.moceanapi.com/?php#receive-sms
[doc_verify]: https://docs.moceanapi.com/?php#overview-3
[license]: LICENSE.txt