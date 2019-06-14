<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:02 PM.
 */

namespace MoceanTest\Message;

use MoceanTest\AbstractTesting;

class MessageTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('message.json');
        $this->mockXmlResponseStr = $this->getResponseString('message_v2.xml');

        $this->jsonResponse = \Mocean\Message\Message::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\Message\Message::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-from'        => 'testing from',
            'mocean-to'          => 'testing to',
            'mocean-text'        => 'testing text',
            'mocean-udh'         => 'testing udh',
            'mocean-coding'      => 'testing coding',
            'mocean-dlr-mask'    => 'testing dlr mask',
            'mocean-dlr-url'     => 'testing dlr url',
            'mocean-schedule'    => 'testing schedule',
            'mocean-mclass'      => 'testing mclass',
            'mocean-alt-dcs'     => 'testing alt dcs',
            'mocean-charset'     => 'testing charset',
            'mocean-validity'    => 'testing validity',
        ];
        $req = new \Mocean\Message\Message('testing from', 'testing to', 'testing-text', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Message\Message('testing from', 'testing to', 'testing-text');
        $setterReq->setFrom('testing from');
        $setterReq->setTo('testing to');
        $setterReq->setText('testing text');
        $setterReq->setUdh('testing udh');
        $setterReq->setCoding('testing coding');
        $setterReq->setDlrMask('testing dlr mask');
        $setterReq->setDlrUrl('testing dlr url');
        $setterReq->setSchedule('testing schedule');
        $setterReq->setMclass('testing mclass');
        $setterReq->setAltDcs('testing alt dcs');
        $setterReq->setCharset('testing charset');
        $setterReq->setValidity('testing validity');
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

    public function testDirectAccessResponseData()
    {
        $this->objectTesting(\Mocean\Message\Message::createFromResponse($this->mockJsonResponseStr, '1'));
        $this->objectTesting(\Mocean\Message\Message::createFromResponse($this->getResponseString('message.xml'), '1'));

        $this->objectTesting($this->jsonResponse);
        $this->objectTesting($this->xmlResponse);
    }

    public function testObjectErrorWhenCreateFromResponseWithStatus0()
    {
        try {
            \Mocean\Message\Message::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    private function objectTesting($res)
    {
        $this->assertEquals($res->messages[0]->status, '0');
        $this->assertEquals($res->messages[0]->receiver, '60123456789');
        $this->assertEquals($res->messages[0]->msgid, 'CPASS_restapi_C0000002737000000.0001');
        $this->assertCount(1, $res->messages);

        $this->assertEquals($res['messages'][0]['status'], '0');
        $this->assertEquals($res['messages'][0]['receiver'], '60123456789');
        $this->assertEquals($res['messages'][0]['msgid'], 'CPASS_restapi_C0000002737000000.0001');
        $this->assertCount(1, $res['messages']);
    }
}
