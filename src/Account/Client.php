<?php

namespace Mocean\Account;

use GuzzleHttp\Psr7\Request;
use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public function getPricing($price = [])
    {
        if (!($price instanceof ModelInterface)) {
            if (!\is_array($price)) {
                throw new \RuntimeException('price must implement `'.ModelInterface::class.'` or be an array`');
            }
            $price = new Price($price);
        }

        $params = $price->getRequestData();

        $request = new Request(
            'GET',
            '/account/pricing?'.http_build_query($params)
        );

        $response = $this->client->send($request);
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return Price::createFromResponse($data, $this->client->version);
    }

    public function getBalance($balance = [])
    {
        if (!($balance instanceof ModelInterface)) {
            if (!\is_array($balance)) {
                throw new \RuntimeException('balance must implement `'.ModelInterface::class.'` or be an array`');
            }
            $balance = new Balance($balance);
        }

        $params = $balance->getRequestData();

        $request = new Request(
            'GET',
            '/account/balance?'.http_build_query($params)
        );

        $response = $this->client->send($request);
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return Balance::createFromResponse($data, $this->client->version);
    }
}
