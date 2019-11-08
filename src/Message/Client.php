<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Message;

use GuzzleHttp\Psr7\Request;
use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;

/**
 * Class Client.
 */
class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public function send($message)
    {
        if (!($message instanceof ModelInterface)) {
            $message = $this->createMessageFromArray($message);
        }

        $params = $message->getRequestData();

        $request = new Request(
            'POST',
            '/sms',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
        $response = $this->client->send($request);
        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
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
            'GET',
            '/report/message?'.http_build_query($params)
        );

        $response = $this->client->send($request);

        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
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
}
