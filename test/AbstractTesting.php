<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 2:51 PM.
 */

namespace MoceanTest;

use GuzzleHttp\Psr7\Response;
use Http\Message\RequestMatcher\RequestMatcher;
use Http\Mock\Client as HttpMockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

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

    protected function makeMockHttpClient($callback)
    {
        $mockClient = new HttpMockClient();
        $mockClient->on(new RequestMatcher(), $callback);

        return $mockClient;
    }

    protected function makeMoceanClientWithMockHttpClient(HttpMockClient $mockHttpClient)
    {
        return new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret), [], $mockHttpClient);
    }

    protected function makeMoceanClientWithEmptyResponse()
    {
        return new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret), [], $this->makeMockHttpClient(new Response()));
    }

    protected function getContentFromRequest(RequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            $body = $request->getUri()->getQuery();
        } else {
            $request->getBody()->rewind();
            $body = $request->getBody()->getContents();
        }

        parse_str($body, $output);

        return $output;
    }

    protected function getTestUri($uri, $version = '2')
    {
        return "/rest/$version$uri";
    }
}
