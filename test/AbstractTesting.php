<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 2:51 PM.
 */

namespace MoceanTest;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client as HttpMockClient;
use PHPUnit\Framework\TestCase;

class AbstractTesting extends TestCase
{
    protected $apiKey = 'test_api_key';
    protected $apiSecret = 'test_api_secret';
    protected $defaultVersion = '2';

    public function getClass($class, $property, $object)
    {
        $reflectionClass = new \ReflectionClass($class);
        $refProperty = $reflectionClass->getProperty($property);
        $refProperty->setAccessible(true);

        return $refProperty->getValue($object);
    }

    protected function getResponse($fileName, $status = 200)
    {
        return new Response($status, [], fopen(__DIR__.'/Resources/'.$fileName, 'r'));
    }

    protected function getResponseString($fileName)
    {
        return file_get_contents(__DIR__.'/Resources/'.$fileName);
    }

    protected function convertArrayFromQueryString($queryStr)
    {
        parse_str($queryStr, $output);

        return $output;
    }

    protected function interceptRequest($fileName = null, $callback = null)
    {
        $mockClient = new HttpMockClient();
        if ($fileName === null) {
            $mockClient->addResponse(new Response());
        } else {
            $mockClient->addResponse($this->getResponse($fileName));
        }
        $client = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret), [], $mockClient);
        if (is_callable($callback)) {
            $callback($client, $mockClient);
        }

        return $client;
    }

    protected function getTestUri($uri, $version = '2')
    {
        return "/rest/$version$uri";
    }
}
