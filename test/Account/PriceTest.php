<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:22 PM.
 */

namespace MoceanTest\Account;

use MoceanTest\AbstractTesting;

class PriceTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('price.json');
        $this->mockXmlResponseStr = $this->getResponseString('price_v2.xml');

        $this->jsonResponse = \Mocean\Account\Price::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\Account\Price::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-mcc'         => 'test mcc',
            'mocean-mnc'         => 'test mnc',
            'mocean-delimiter'   => 'test delimiter',
        ];
        $req = new \Mocean\Account\Price($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Account\Price();
        $setterReq->setResponseFormat('json');
        $setterReq->setMcc('test mcc');
        $setterReq->setMnc('test mnc');
        $setterReq->setDelimiter('test delimiter');

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

    public function testDirectAccessResponseData()
    {
        $this->objectTesting(\Mocean\Account\Price::createFromResponse($this->mockJsonResponseStr, '1'));
        $this->objectTesting(\Mocean\Account\Price::createFromResponse($this->getResponseString('price.xml'), '1'));

        $this->objectTesting($this->jsonResponse);
        $this->objectTesting($this->xmlResponse);
    }

    public function testObjectErrorWhenCreateFromResponseWithStatus0()
    {
        try {
            \Mocean\Account\Price::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    private function objectTesting($res)
    {
        $this->assertCount(25, $res->destinations);
        $this->assertEquals($res->status, '0');
        $this->assertEquals($res->destinations[0]->country, 'Default');
        $this->assertEquals($res->destinations[0]->operator, 'Default');
        $this->assertEquals($res->destinations[0]->mcc, 'Default');
        $this->assertEquals($res->destinations[0]->mnc, 'Default');
        $this->assertEquals($res->destinations[0]->price, '2.0000');
        $this->assertEquals($res->destinations[0]->currency, 'MYR');

        $this->assertCount(25, $res['destinations']);
        $this->assertEquals($res['status'], '0');
        $this->assertEquals($res['destinations'][0]['country'], 'Default');
        $this->assertEquals($res['destinations'][0]['operator'], 'Default');
        $this->assertEquals($res['destinations'][0]['mcc'], 'Default');
        $this->assertEquals($res['destinations'][0]['mnc'], 'Default');
        $this->assertEquals($res['destinations'][0]['price'], '2.0000');
        $this->assertEquals($res['destinations'][0]['currency'], 'MYR');
    }
}
