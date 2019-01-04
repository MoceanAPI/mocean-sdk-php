<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:54 PM.
 */

namespace MoceanTest\Verify;

use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;

class VerifyCodeTesting extends AbstractTesting
{
    use ResponseTrait;

    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString(__DIR__.'/responses/verify_code.json');
        $this->mockXmlResponseStr = $this->getResponseString(__DIR__.'/responses/verify_code.xml');

        $this->jsonResponse = \Mocean\Verify\VerifyCode::createFromResponse($this->mockJsonResponseStr);
        $this->xmlResponse = \Mocean\Verify\VerifyCode::createFromResponse($this->mockXmlResponseStr);
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

    public function testDirectAccessResponseDataUsingArray()
    {
        $this->assertEquals($this->jsonResponse['status'], '0');
        $this->assertEquals($this->jsonResponse['reqid'], 'CPASS_restapi_C0000002737000000.0002');

        $this->assertEquals($this->xmlResponse['status'], '0');
        $this->assertEquals($this->xmlResponse['reqid'], 'CPASS_restapi_C0000002737000000.0002');
    }

    public function testDirectAccessResponseDataUsingMagicProperties()
    {
        $this->assertEquals($this->jsonResponse->status, '0');
        $this->assertEquals($this->jsonResponse->reqid, 'CPASS_restapi_C0000002737000000.0002');

        $this->assertEquals($this->xmlResponse->status, '0');
        $this->assertEquals($this->xmlResponse->reqid, 'CPASS_restapi_C0000002737000000.0002');
    }
}
