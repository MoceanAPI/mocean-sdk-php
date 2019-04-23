<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 4/8/2019
 * Time: 10:21 AM.
 */

namespace MoceanTest\NumberLookup;


use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;

class NumberLookupTesting extends AbstractTesting
{
    use ResponseTrait;

    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString(__DIR__ . '/responses/number_lookup.json');
        $this->mockXmlResponseStr = $this->getResponseString(__DIR__ . '/responses/number_lookup.xml');

        $this->jsonResponse = \Mocean\NumberLookup\NumberLookup::createFromResponse($this->mockJsonResponseStr);
        $this->xmlResponse = \Mocean\NumberLookup\NumberLookup::createFromResponse($this->mockXmlResponseStr);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-to' => 'testing to',
        ];

        $req = new \Mocean\NumberLookup\NumberLookup('testing to', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\NumberLookup\NumberLookup('testing to');
        $setterReq->setTo('testing to');
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\NumberLookup\NumberLookup::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\NumberLookup\NumberLookup::class, $this->xmlResponse);
    }

    public function testObjectToStringFunction()
    {
        $this->assertEquals($this->jsonResponse, $this->mockJsonResponseStr);
        $this->assertEquals($this->xmlResponse, $this->mockXmlResponseStr);
    }

    public function testDirectAccessResponseDataUsingArray()
    {
        $this->assertEquals($this->jsonResponse['status'], '0');
        $this->assertEquals($this->jsonResponse['msgid'], 'CPASS_restapi_C00000000000000.0002');

        $this->assertEquals($this->xmlResponse['status'], '0');
        $this->assertEquals($this->xmlResponse['msgid'], 'CPASS_restapi_C00000000000000.0002');
    }

    public function testDirectAccessResponseDataUsingMagicProperties()
    {
        $this->assertEquals($this->jsonResponse->status, '0');
        $this->assertEquals($this->jsonResponse->msgid, 'CPASS_restapi_C00000000000000.0002');

        $this->assertEquals($this->xmlResponse->status, '0');
        $this->assertEquals($this->xmlResponse->msgid, 'CPASS_restapi_C00000000000000.0002');
    }
}
