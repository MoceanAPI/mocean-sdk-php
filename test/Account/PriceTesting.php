<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:22 PM.
 */

namespace MoceanTest\Account;

use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;

class PriceTesting extends AbstractTesting
{
    use ResponseTrait;

    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString(__DIR__.'/responses/price.json');
        $this->mockXmlResponseStr = $this->getResponseString(__DIR__.'/responses/price.xml');

        $this->jsonResponse = \Mocean\Account\Price::createFromResponse($this->mockJsonResponseStr);
        $this->xmlResponse = \Mocean\Account\Price::createFromResponse($this->mockXmlResponseStr);
    }

    public function testRequestDataParams()
    {
        $params = ['mocean-resp-format' => 'json'];
        $req = new \Mocean\Account\Price($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Account\Price();
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\Account\Price::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\Account\Price::class, $this->xmlResponse);
    }

    public function testObjectToStringFunction()
    {
        $this->assertEquals($this->jsonResponse, $this->mockJsonResponseStr);
        $this->assertEquals($this->xmlResponse, $this->mockXmlResponseStr);
    }

    public function testDirectAccessResponseDataUsingArray()
    {
        $this->assertEquals($this->jsonResponse['status'], '0');
        $this->assertEquals($this->jsonResponse['destinations'][0]['country'], 'Default');
        $this->assertCount(25, $this->jsonResponse['destinations']);

        $this->assertEquals($this->xmlResponse['status'], '0');
        $this->assertEquals($this->xmlResponse['data']['destination'][0]['country'], 'Default');
        $this->assertCount(25, $this->xmlResponse['data']['destination']);
    }

    public function testDirectAccessResponseDataUsingMagicProperties()
    {
        $this->assertEquals($this->jsonResponse->status, '0');
        $this->assertEquals($this->jsonResponse->destinations[0]->country, 'Default');
        $this->assertCount(25, $this->jsonResponse->destinations);

        $this->assertEquals($this->xmlResponse->status, '0');
        $this->assertEquals($this->xmlResponse->data->destination[0]->country, 'Default');
        $this->assertCount(25, $this->xmlResponse->data->destination);
    }
}
