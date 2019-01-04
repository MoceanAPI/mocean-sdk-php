<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 4:53 PM
 */

namespace MoceanTest\Message;


use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class ClientTesting extends AbstractTesting
{
    use ResponseTrait;

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
        $inputParams = array(
            'mocean-to' => 'testing to',
            'mocean-from' => 'testing from',
            'mocean-text' => 'testing text',
        );

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('rest.moceanapi.com', $request->getUri()->getHost());
            $this->assertEquals('/rest/1/sms', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-from'], $queryArr['mocean-from']);
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-text'], $queryArr['mocean-text']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__ . '/responses/message.xml'));

        $messageRes = $this->mockMessageClient->send($inputParams);
        $this->assertInstanceOf(\Mocean\Message\Message::class, $messageRes);
    }

    public function testGetMessageStatus()
    {
        $inputParams = array(
            'mocean-msgid' => 'testing msgid',
        );

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('rest.moceanapi.com', $request->getUri()->getHost());
            $this->assertEquals('/rest/1/report/message', $request->getUri()->getPath());

            $queryArr = $this->convertArrayFromQueryString($request->getUri()->getQuery());
            $this->assertEquals($inputParams['mocean-msgid'], $queryArr['mocean-msgid']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__ . '/responses/message_status.xml'));

        $messageStatusRes = $this->mockMessageClient->search($inputParams);
        $this->assertInstanceOf(\Mocean\Message\MessageStatus::class, $messageStatusRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSendParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->moceanClient->message()->send('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSearchParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->moceanClient->message()->search('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testSendRequiredRequestParamNotPresent()
    {
        $this->moceanClient->message()->send(array());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-msgid`
     */
    public function testSearchRequiredRequestParamNotPresent()
    {
        $this->moceanClient->message()->search(array());
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testSendIfTheresErrorResponse()
    {
        $this->moceanClient->message()->send(array(
            'mocean-to' => 'testing to',
            'mocean-from' => 'testing from',
            'mocean-text' => 'testing text'
        ));
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testSearchIfTheresErrorResponse()
    {
        $this->moceanClient->message()->search(array(
            'mocean-msgid' => 'testing msg id'
        ));
    }
}
