<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:22 PM.
 */

namespace MoceanTest\Account;

use MoceanTest\AbstractTesting;

class BalanceTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('balance.json');
        $this->mockXmlResponseStr = $this->getResponseString('balance.xml');

        $this->jsonResponse = \Mocean\Account\Balance::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\Account\Balance::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
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

    public function testDirectAccessResponseData()
    {
        $this->objectTesting($this->jsonResponse);
        $this->objectTesting($this->xmlResponse);
    }

    public function testObjectErrorWhenCreateFromResponseWithStatus0()
    {
        try {
            \Mocean\Account\Balance::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    private function objectTesting($res)
    {
        $this->assertEquals($res->status, '0');
        $this->assertEquals($res->value, '100.0000');

        $this->assertEquals($res['status'], '0');
        $this->assertEquals($res['value'], '100.0000');
    }
}
