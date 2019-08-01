<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 4:53 PM.
 */

namespace MoceanTest\Message;

use MoceanTest\AbstractTesting;

class ClientTest extends AbstractTesting
{
    public function testSendMessage()
    {
        $this->interceptRequest('message.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-to'   => 'testing to',
                'mocean-from' => 'testing from',
                'mocean-text' => 'testing text',
            ];

            $messageRes = $client->message()->send($inputParams);
            $this->assertInstanceOf(\Mocean\Message\Message::class, $messageRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/sms'), $httpClient->getLastRequest()->getUri()->getPath());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-from'], $queryArr['mocean-from']);
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-text'], $queryArr['mocean-text']);
        });
    }

    public function testGetMessageStatus()
    {
        $this->interceptRequest('message_status.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-msgid' => 'testing msgid',
            ];

            $messageStatusRes = $client->message()->search($inputParams);
            $this->assertInstanceOf(\Mocean\Message\MessageStatus::class, $messageStatusRes);

            $this->assertEquals('GET', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/report/message'), $httpClient->getLastRequest()->getUri()->getPath());
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getUri()->getQuery());
            $this->assertEquals($inputParams['mocean-msgid'], $queryArr['mocean-msgid']);
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSendParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->message()->send('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSearchParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->message()->search('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testSendRequiredRequestParamNotPresent()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->message()->send([]);

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-msgid`
     */
    public function testSearchRequiredRequestParamNotPresent()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->message()->search([]);

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    public function testResponseDataIsEmpty()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client) {
            try {
                $client->message()->search(['mocean-msgid' => 'testing msgid']);
                $this->fail();
            } catch (\Mocean\Client\Exception\Exception $e) {
            }

            try {
                $client->message()->send([
                    'mocean-to'   => 'testing to',
                    'mocean-from' => 'testing from',
                    'mocean-text' => 'testing text',
                ]);
                $this->fail();
            } catch (\Mocean\Client\Exception\Exception $e) {
            }
        });
    }
}
