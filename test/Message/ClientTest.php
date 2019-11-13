<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 4:53 PM.
 */

namespace MoceanTest\Message;

use MoceanTest\AbstractTesting;
use Psr\Http\Message\RequestInterface;

class ClientTest extends AbstractTesting
{
    public function testSendMessage()
    {
        $inputParams = [
            'mocean-to'   => 'testing to',
            'mocean-from' => 'testing from',
            'mocean-text' => 'testing text',
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/sms'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-from'], $body['mocean-from']);
            $this->assertEquals($inputParams['mocean-to'], $body['mocean-to']);
            $this->assertEquals($inputParams['mocean-text'], $body['mocean-text']);

            return $this->getResponse('message.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $messageRes = $client->message()->send($inputParams);
        $this->assertInstanceOf(\Mocean\Message\Message::class, $messageRes);
    }

    public function testGetMessageStatus()
    {
        $inputParams = [
            'mocean-msgid' => 'testing msgid',
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals($this->getTestUri('/report/message'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-msgid'], $body['mocean-msgid']);

            return $this->getResponse('message_status.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $messageStatusRes = $client->message()->search($inputParams);
        $this->assertInstanceOf(\Mocean\Message\MessageStatus::class, $messageStatusRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSendParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->message()->send('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSearchParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->message()->search('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testSendRequiredRequestParamNotPresent()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->message()->send([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-msgid`
     */
    public function testSearchRequiredRequestParamNotPresent()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->message()->search([]);
    }

    public function testResponseDataIsEmpty()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

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
    }
}
