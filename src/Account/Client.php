<?php

namespace Mocean\Account;

use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;

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
            '/account/pricing?'.http_build_query($params),
            'GET',
            'php://temp'
        );

        $response = $this->client->send($request);
        $data = $response->getBody()->getContents();

        if ($data === '') {
            throw new Exception\Server('No results found');
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
            '/account/balance?'.http_build_query($params),
            'GET',
            'php://temp'
        );

        $response = $this->client->send($request);
        $data = $response->getBody()->getContents();

        if ($data === '') {
            throw new Exception\Server('No results found');
        }

        return Balance::createFromResponse($data, $this->client->version);
    }

    protected function getException(ResponseInterface $response, $application = null)
    {
        $body = json_decode($response->getBody()->getContents(), true);
        $status = $response->getStatusCode();

        if ($status >= 400 and $status < 500) {
            $e = new Exception\Request($body['error_title'], $status);
        } elseif ($status >= 500 and $status < 600) {
            $e = new Exception\Server($body['error_title'], $status);
        } else {
            $e = new Exception\Exception('Unexpected HTTP Status Code');
        }

        return $e;
    }
}
