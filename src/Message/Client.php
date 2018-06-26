<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Message;

use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Zend\Diactoros\Request;

/**
 * Class Client
 * @method Text sendText(string $to, string $from, string $text, array $additional = []) Send a Test Message
 */
class Client implements ClientAwareInterface
{
    use ClientAwareTrait;
	
    /**
     * @param Message|array $message
     * @return Message
     * @throws Exception\Exception
     * @throws Exception\Request
     * @throws Exception\Server
     */
	protected $delivery_status = [1 => 'Success', 2 => 'Failed', 3 => 'Expired'];

    public function send($message)
    {
        if(!($message instanceof MessageInterface))
		{
            $message = $this->createMessageFromArray($message);
        }
		
        $params = $message;

		$request = new Request(
            \Mocean\Client::BASE_REST . '/sms',
			'POST',
			'php://temp',
			['content-type' => 'application/x-www-form-urlencoded']
        );
		
        $request->getBody()->write(http_build_query($params));
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

    public function search($message)
    {
		$params = $message;
	
        $request = new Request(
            \Mocean\Client::BASE_REST . '/report/message?'.http_build_query($params),
            'GET',
            'php://temp'
        );

        $response = $this->client->send($request);
		
		$response->getBody()->rewind();		
		$data = $response->getBody()->getContents();
		
        if(!$data){
            throw new Exception\Request('no message found for `' . $params['mocean-msgid'] . '`');
        }
		
        if($response->getStatusCode() > 203)
		{
            throw new Exception\Request('error status from API', $response->getStatusCode());
        } 
		
		$this->createResponseHeader($response->getHeaders()['Content-type'][0]);
        return $data;
    }

	public function receiveDLR()
    {
		parse_str(file_get_contents("php://input"), $data); 
		
		if(isset($data['mocean-dlr-status']))
		{
			$data['mocean-dlr-status'] = $this->delivery_status[$data['mocean-dlr-status']];
		}
		$data_json = json_encode($data);

        return $data_json;
    }

    protected function createMessageFromArray($message)
    {
        if(!is_array($message)){
            throw new \RuntimeException('message must implement `' . MessageInterface::class . '` or be an array`');
        }
		
        foreach(['mocean-to', 'mocean-from'] as $param){
            if(!isset($message[$param])){
                throw new \InvalidArgumentException('missing expected key `' . $param . '`');
            }
        }
		
        $to = $message['mocean-to'];
        $from = $message['mocean-from'];

        unset($message['mocean-to']);
        unset($message['mocean-from']);
		
        $this->requestData['mocean-to'] = (string) $to;
        $this->requestData['mocean-from'] = (string) $from;
		
        $this->requestData = array_merge($this->requestData, $message);
        return $this->requestData;
    }

	protected function createResponseHeader($header)
	{
		Header('Content-type: '.$header);
		return true;
	}

	public function count()
    {
        $data = $this->getResponseData();
        if(!isset($data['messages'])){
            return 0;
        }

        return count($data['messages']);
    }
    
    /**
     * Convenience feature allowing messages to be sent without creating a message object first.
     *
     * @param $name
     * @param $arguments
     * @return MessageInterface
     */
    public function __call($name, $arguments)
    {
        if(!(strstr($name, 'send') !== 0)){
            throw new \RuntimeException(sprintf(
                '`%s` is not a valid method on `%s`',
                $name,
                get_class($this)
            ));
        }

        $class = substr($name, 4);
        $class = 'Mocean\\Message\\' . ucfirst(strtolower($class));

        if(!class_exists($class)){
            throw new \RuntimeException(sprintf(
                '`%s` is not a valid method on `%s`',
                $name,
                get_class($this)
            ));
        }

        $reflection = new \ReflectionClass($class);
        $message = $reflection->newInstanceArgs($arguments);

        return $this->send($message);
    }

	
}