<?php

namespace Mocean\Account;

use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Network;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;
use Mocean\Client\Exception;


class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public function getPricing($message)
    {
        $params = $message;
		
        $request = new Request(
            \Mocean\Client::BASE_REST . '/account/pricing?'.http_build_query($params),
            'GET',
            'php://temp'
        );
		
        $response = $this->client->send($request);
        $data = $response->getBody()->getContents();

        if ($data === '') {
            throw new Exception\Server('No results found');
        }
		$this->createResponseHeader($response->getHeaders()['Content-type'][0]);
		
        return $data;
    }

    public function getBalance($message)
    {
		$params = $message;

        $request = new Request(
            \Mocean\Client::BASE_REST . '/account/balance?'.http_build_query($params),
            'GET',
            'php://temp'
        );
		
        $response = $this->client->send($request);
        $data = $response->getBody()->getContents();
		
		
        if ($data === '') {
            throw new Exception\Server('No results found');
        }

		$this->createResponseHeader($response->getHeaders()['Content-type'][0]);
        return $data;
    }
	
	protected function createResponseHeader($header)
	{
		Header('Content-type: '.$header);
		return true;
	}

    protected function getException(ResponseInterface $response, $application = null)
    {
        $body = json_decode($response->getBody()->getContents(), true);
        $status = $response->getStatusCode();

        if($status >= 400 AND $status < 500) {
            $e = new Exception\Request($body['error_title'], $status);
        } elseif($status >= 500 AND $status < 600) {
            $e = new Exception\Server($body['error_title'], $status);
        } else {
            $e = new Exception\Exception('Unexpected HTTP Status Code');
        }

        return $e;
    }

}