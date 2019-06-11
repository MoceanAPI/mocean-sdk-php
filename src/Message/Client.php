<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Message;

use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;
use Zend\Diactoros\Request;

/**
 * Class Client.
 */
class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    /**
     * @param Message|array $message
     *
     * @throws Exception\Exception
     * @throws Exception\Request
     * @throws Exception\Server
     *
     * @return Message
     */
    protected $delivery_status = [1 => 'Success', 2 => 'Failed', 3 => 'Expired'];

    public function send($message)
    {
        if (!($message instanceof ModelInterface)) {
            $message = $this->createMessageFromArray($message);
        }

        $params = $message->getRequestData();

        $request = new Request(
            '/sms',
            'POST',
            'php://temp',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
        $response = $this->client->send($request);
        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data)) {
            throw new Exception\Exception('unexpected response from API');
        }

        return Message::createFromResponse($data, $this->client->version);
    }

    public function search($messageStatus)
    {
        if (!($messageStatus instanceof ModelInterface)) {
            if (!\is_array($messageStatus)) {
                throw new \RuntimeException('message status must implement `'.ModelInterface::class.'` or be an array`');
            }

            if (!isset($messageStatus['mocean-msgid'])) {
                throw new \InvalidArgumentException('missing expected key `mocean-msgid`');
            }

            $msgId = $messageStatus['mocean-msgid'];
            unset($messageStatus['mocean-msgid']);
            $messageStatus = new MessageStatus($msgId, $messageStatus);
        }
        $params = $messageStatus->getRequestData();

        $request = new Request(
            '/report/message?'.http_build_query($params),
            'GET',
            'php://temp'
        );

        $response = $this->client->send($request);

        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data)) {
            throw new Exception\Exception('unexpected response from API');
        }

        return MessageStatus::createFromResponse($data, $this->client->version);
    }

    protected function createMessageFromArray($message)
    {
        if (!is_array($message)) {
            throw new \RuntimeException('message must implement `'.ModelInterface::class.'` or be an array`');
        }

        foreach (['mocean-to', 'mocean-from', 'mocean-text'] as $param) {
            if (!isset($message[$param])) {
                throw new \InvalidArgumentException('missing expected key `'.$param.'`');
            }
        }

        $to = $message['mocean-to'];
        $from = $message['mocean-from'];
        $text = $message['mocean-text'];

        unset($message['mocean-to'], $message['mocean-from'], $message['mocean-text']);

        return new Message($from, $to, $text, $message);
    }

    public function count()
    {
        $data = $this->getResponseData();
        if (!isset($data['messages'])) {
            return 0;
        }

        return count($data['messages']);
    }

    /**
     * Convenience feature allowing messages to be sent without creating a message object first.
     *
     * @param $name
     * @param $arguments
     *
     * @return MessageInterface
     */
    public function __call($name, $arguments)
    {
        if (!(strstr($name, 'send') !== 0)) {
            throw new \RuntimeException(sprintf(
                '`%s` is not a valid method on `%s`',
                $name,
                get_class($this)
            ));
        }

        $class = substr($name, 4);
        $class = 'Mocean\\Message\\'.ucfirst(strtolower($class));

        if (!class_exists($class)) {
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
