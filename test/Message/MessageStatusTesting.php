<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:21 PM
 */

namespace MoceanTest\Message;


use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;

class MessageStatusTesting extends AbstractTesting
{
    use ResponseTrait;

    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString(__DIR__ . '/responses/message_status.json');
        $this->mockXmlResponseStr = $this->getResponseString(__DIR__ . '/responses/message_status.xml');

        $this->jsonResponse = \Mocean\Message\MessageStatus::createFromResponse($this->mockJsonResponseStr);
        $this->xmlResponse = \Mocean\Message\MessageStatus::createFromResponse($this->mockXmlResponseStr);
    }


    public function testRequestDataParams()
    {
        $params = array(
            'mocean-resp-format' => 'json',
            'mocean-msgid' => 'CPASS_restapi_C0000002737000000.0001'
        );
        $req = new \Mocean\Message\MessageStatus('CPASS_restapi_C0000002737000000.0001', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Message\MessageStatus('CPASS_restapi_C0000002737000000.0001');
        $setterReq->setMsgId('CPASS_restapi_C0000002737000000.0001');
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\Message\MessageStatus::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\Message\MessageStatus::class, $this->xmlResponse);
    }

    public function testObjectToStringFunction()
    {
        $this->assertEquals($this->jsonResponse, $this->mockJsonResponseStr);
        $this->assertEquals($this->xmlResponse, $this->mockXmlResponseStr);
    }

    public function testDirectAccessResponseDataUsingArray()
    {
        $this->assertEquals($this->jsonResponse['message_status'], 'Transaction not found');
        $this->assertEquals($this->jsonResponse['msgid'], 'CPASS_restapi_C0000002737000000.0001');

        $this->assertEquals($this->xmlResponse['message_status'], 'Transaction not found');
        $this->assertEquals($this->xmlResponse['msgid'], 'CPASS_restapi_C0000002737000000.0001');
    }

    public function testDirectAccessResponseDataUsingMagicProperties()
    {
        $this->assertEquals($this->jsonResponse->message_status, 'Transaction not found');
        $this->assertEquals($this->jsonResponse->msgid, 'CPASS_restapi_C0000002737000000.0001');

        $this->assertEquals($this->xmlResponse->message_status, 'Transaction not found');
        $this->assertEquals($this->xmlResponse->msgid, 'CPASS_restapi_C0000002737000000.0001');
    }
}
