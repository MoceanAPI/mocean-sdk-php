<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:02 PM.
 */

namespace MoceanTest\Message;

use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;

class MessageTesting extends AbstractTesting
{
    use ResponseTrait;

    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString(__DIR__.'/responses/message.json');
        $this->mockXmlResponseStr = $this->getResponseString(__DIR__.'/responses/message.xml');

        $this->jsonResponse = \Mocean\Message\Message::createFromResponse($this->mockJsonResponseStr);
        $this->xmlResponse = \Mocean\Message\Message::createFromResponse($this->mockXmlResponseStr);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-from'        => 'testing from',
            'mocean-to'          => 'testing to',
            'mocean-text'        => 'testing text',
        ];
        $req = new \Mocean\Message\Message('testing from', 'testing to', 'testing-text', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Message\Message('testing from', 'testing to', 'testing-text');
        $setterReq->setFrom('testing from');
        $setterReq->setTo('testing to');
        $setterReq->setText('testing text');
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\Message\Message::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\Message\Message::class, $this->xmlResponse);
    }

    public function testObjectToStringFunction()
    {
        $this->assertEquals($this->jsonResponse, $this->mockJsonResponseStr);
        $this->assertEquals($this->xmlResponse, $this->mockXmlResponseStr);
    }

    public function testDirectAccessResponseDataUsingArray()
    {
        $this->assertEquals($this->jsonResponse['messages'][0]['status'], '0');
        $this->assertEquals($this->jsonResponse['messages'][0]['receiver'], '60123456789');
        $this->assertCount(1, $this->jsonResponse['messages']);

        $this->assertEquals($this->xmlResponse['message']['status'], '0');
        $this->assertEquals($this->xmlResponse['message']['receiver'], '60123456789');
    }

    public function testDirectAccessResponseDataUsingMagicProperties()
    {
        $this->assertEquals($this->jsonResponse->messages[0]->status, '0');
        $this->assertEquals($this->jsonResponse->messages[0]->receiver, '60123456789');
        $this->assertCount(1, $this->jsonResponse->messages);

        $this->assertEquals($this->xmlResponse->message->status, '0');
        $this->assertEquals($this->xmlResponse->message->receiver, '60123456789');
    }
}
