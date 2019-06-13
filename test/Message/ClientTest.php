<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 4:53 PM.
 */

namespace MoceanTest\Message;

use MoceanTest\AbstractTesting;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class ClientTest extends AbstractTesting
{
    /** @var \Mocean\Client $moceanClient */
    protected $moceanClient;

    protected $mockMoceanClient;
    /** @var \Mocean\Message\Client $mockAccountClient */
    protected $mockMessageClient;

    public function setUp()
    {
        $this->moceanClient = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $this->mockMoceanClient = $this->prophesize('Mocean\Client');
        $this->mockMessageClient = new \Mocean\Message\Client();
        $this->mockMessageClient->setClient($this->mockMoceanClient->reveal());
    }

    public function testSendMessage()
    {
        $inputParams = [
            'mocean-to'   => 'testing to',
            'mocean-from' => 'testing from',
            'mocean-text' => 'testing text',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('/sms', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-from'], $queryArr['mocean-from']);
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-text'], $queryArr['mocean-text']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('message.xml'));

        $messageRes = $this->mockMessageClient->send($inputParams);
        $this->assertInstanceOf(\Mocean\Message\Message::class, $messageRes);
    }

    public function testGetMessageStatus()
    {
        $inputParams = [
            'mocean-msgid' => 'testing msgid',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('/report/message', $request->getUri()->getPath());

            $queryArr = $this->convertArrayFromQueryString($request->getUri()->getQuery());
            $this->assertEquals($inputParams['mocean-msgid'], $queryArr['mocean-msgid']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('message_status.xml'));

        $messageStatusRes = $this->mockMessageClient->search($inputParams);
        $this->assertInstanceOf(\Mocean\Message\MessageStatus::class, $messageStatusRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSendParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->mockMessageClient->send('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSearchParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->mockMessageClient->search('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testSendRequiredRequestParamNotPresent()
    {
        $this->mockMessageClient->send([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-msgid`
     */
    public function testSearchRequiredRequestParamNotPresent()
    {
        $this->mockMessageClient->search([]);
    }

    public function testResponseDataIsEmpty()
    {
        $this->mockMoceanClient->send(Argument::that(function () {
            return true;
        }))->shouldBeCalledTimes(2)->willReturn(new Response());

        try {
            $this->mockMessageClient->search(['mocean-msgid' => 'testing msgid']);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }

        try {
            $this->mockMessageClient->send([
                'mocean-to'   => 'testing to',
                'mocean-from' => 'testing from',
                'mocean-text' => 'testing text',
            ]);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }
}
