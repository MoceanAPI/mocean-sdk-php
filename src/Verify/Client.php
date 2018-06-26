<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Verify;

use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public function start($verification)
    {
        $request = new Request(
            \Mocean\Client::BASE_REST . '/verify/req',
            'POST',
            'php://temp',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($verification));
        $response = $this->client->send($request);
		$this->createResponseHeader($response->getHeaders()['Content-type'][0]);
		$response->getBody()->rewind();		
		$data = $response->getBody()->getContents();
		if(!isset($data))
		{
            throw new Exception\Exception('unexpected response from API');
        }
		
        return $data;
    }

    public function check($verification)
    {
        $request = new Request(
            \Mocean\Client::BASE_REST . '/verify/check',
            'POST',
            'php://temp',
            ['content-type' => 'application/x-www-form-urlencoded']
        );
        
        $request->getBody()->write(http_build_query($verification));
        $response = $this->client->send($request);
		$this->createResponseHeader($response->getHeaders()['Content-type'][0]);
		$response->getBody()->rewind();		
		$data = $response->getBody()->getContents();
		
		if(!isset($data))
		{
            throw new Exception\Exception('unexpected response from API');
        }
		
        return $data;
    }
	
	protected function createResponseHeader($header)
	{
		Header('Content-type: '.$header);
		return true;
	}
}