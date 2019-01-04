<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:36 PM
 */

namespace MoceanTest\Verify;


use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;

class SendCodeTesting extends AbstractTesting
{
    use ResponseTrait;

    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString(__DIR__ . '/responses/send_code.json');
        $this->mockXmlResponseStr = $this->getResponseString(__DIR__ . '/responses/send_code.xml');;

        $this->jsonResponse = \Mocean\Verify\SendCode::createFromResponse($this->mockJsonResponseStr);
        $this->xmlResponse = \Mocean\Verify\SendCode::createFromResponse($this->mockXmlResponseStr);
    }


    public function testRequestDataParams()
    {
        $params = array(
            'mocean-resp-format' => 'json',
            'mocean-to' => 'testing to',
            'mocean-brand' => 'testing brand'
        );
        $req = new \Mocean\Verify\SendCode('testing to', 'testing brand', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Verify\SendCode('testing to', 'testing brand');
        $setterReq->setTo('testing to');
        $setterReq->setBrand('testing brand');
        $setterReq->setResponseFormat('json');

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
