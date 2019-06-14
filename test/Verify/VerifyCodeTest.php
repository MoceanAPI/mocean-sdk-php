<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:54 PM.
 */

namespace MoceanTest\Verify;

use MoceanTest\AbstractTesting;

class VerifyCodeTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('verify_code.json');
        $this->mockXmlResponseStr = $this->getResponseString('verify_code.xml');

        $this->jsonResponse = \Mocean\Verify\VerifyCode::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\Verify\VerifyCode::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-reqid'       => 'testing reqid',
            'mocean-code'        => 'testing code',
        ];
        $req = new \Mocean\Verify\VerifyCode('testing reqid', 'testing code', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Verify\VerifyCode('testing reqid', 'testing code');
        $setterReq->setReqId('testing reqid');
        $setterReq->setCode('testing code');
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\Verify\VerifyCode::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\Verify\VerifyCode::class, $this->xmlResponse);
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
            \Mocean\Verify\VerifyCode::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    private function objectTesting($res)
    {
        $this->assertEquals($res->status, '0');
        $this->assertEquals($res->reqid, 'CPASS_restapi_C0000002737000000.0002');

        $this->assertEquals($res['status'], '0');
        $this->assertEquals($res['reqid'], 'CPASS_restapi_C0000002737000000.0002');
    }
}
