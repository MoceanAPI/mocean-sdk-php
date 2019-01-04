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

class BalanceTesting extends AbstractTesting
{
    use ResponseTrait;

    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString(__DIR__.'/responses/balance.json');
        $this->mockXmlResponseStr = $this->getResponseString(__DIR__.'/responses/balance.xml');

        $this->jsonResponse = \Mocean\Account\Balance::createFromResponse($this->mockJsonResponseStr);
        $this->xmlResponse = \Mocean\Account\Balance::createFromResponse($this->mockXmlResponseStr);
    }

    public function testRequestDataParams()
    {
        $params = ['mocean-resp-format' => 'json'];
        $req = new \Mocean\Account\Balance($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Account\Balance();
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\Account\Balance::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\Account\Balance::class, $this->xmlResponse);
    }

    public function testObjectToStringFunction()
    {
        $this->assertEquals($this->jsonResponse, $this->mockJsonResponseStr);
        $this->assertEquals($this->xmlResponse, $this->mockXmlResponseStr);
    }

    public function testDirectAccessResponseDataUsingArray()
    {
        $this->assertEquals($this->jsonResponse['status'], '0');
        $this->assertEquals($this->jsonResponse['value'], '100.0000');

        $this->assertEquals($this->xmlResponse['status'], '0');
        $this->assertEquals($this->xmlResponse['value'], '100.0000');
    }

    public function testDirectAccessResponseDataUsingMagicProperties()
    {
        $this->assertEquals($this->jsonResponse->status, '0');
        $this->assertEquals($this->jsonResponse->value, '100.0000');

        $this->assertEquals($this->xmlResponse->status, '0');
        $this->assertEquals($this->xmlResponse->value, '100.0000');
    }
}
