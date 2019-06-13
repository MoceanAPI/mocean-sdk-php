<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:21 PM.
 */

namespace MoceanTest\Message;

use MoceanTest\AbstractTesting;

class MessageStatusTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('message_status.json');
        $this->mockXmlResponseStr = $this->getResponseString('message_status.xml');

        $this->jsonResponse = \Mocean\Message\MessageStatus::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\Message\MessageStatus::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-msgid'       => 'CPASS_restapi_C0000002737000000.0001',
        ];
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

    public function testDirectAccessResponseData()
    {
        $this->objectTesting($this->jsonResponse);
        $this->objectTesting($this->xmlResponse);
    }

    public function testObjectErrorWhenCreateFromResponseWithStatus0()
    {
        try {
            \Mocean\Message\MessageStatus::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    private function objectTesting($res)
    {
        $this->assertEquals($res->status, '0');
        $this->assertEquals($res->message_status, 'Transaction not found');
        $this->assertEquals($res->msgid, 'CPASS_restapi_C0000002737000000.0001');
        $this->assertEquals($res->credit_deducted, '0.0000');

        $this->assertEquals($res['status'], '0');
        $this->assertEquals($res['message_status'], 'Transaction not found');
        $this->assertEquals($res['msgid'], 'CPASS_restapi_C0000002737000000.0001');
        $this->assertEquals($res['credit_deducted'], '0.0000');
    }
}
