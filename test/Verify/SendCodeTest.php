<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:36 PM.
 */

namespace MoceanTest\Verify;

use MoceanTest\AbstractTesting;

class SendCodeTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('send_code.json');
        $this->mockXmlResponseStr = $this->getResponseString('send_code.xml');

        $this->jsonResponse = \Mocean\Verify\SendCode::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\Verify\SendCode::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format'     => 'json',
            'mocean-to'              => 'testing to',
            'mocean-brand'           => 'testing brand',
            'mocean-from'            => 'testing from',
            'mocean-code-length'     => 'testing code length',
            'mocean-pin-validity'    => 'testing pin validity',
            'mocean-next-event-wait' => 'testing next event wait',
        ];
        $req = new \Mocean\Verify\SendCode('testing to', 'testing brand', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Verify\SendCode('testing to', 'testing brand');
        $setterReq->setTo('testing to');
        $setterReq->setBrand('testing brand');
        $setterReq->setResponseFormat('json');
        $setterReq->setFrom('testing from');
        $setterReq->setCodeLength('testing code length');
        $setterReq->setPinValidity('testing pin validity');
        $setterReq->setNextEventWait('testing next event wait');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $this->xmlResponse);
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
            \Mocean\Verify\SendCode::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testResendWithoutReqId()
    {
        (new \Mocean\Verify\SendCode())->resend();
    }

    private function objectTesting($res)
    {
        $this->assertEquals($res->status, '0');
        $this->assertEquals($res->reqid, 'CPASS_restapi_C0000002737000000.0002');

        $this->assertEquals($res['status'], '0');
        $this->assertEquals($res['reqid'], 'CPASS_restapi_C0000002737000000.0002');
    }
}
