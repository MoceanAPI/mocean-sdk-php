<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 4/8/2019
 * Time: 10:21 AM.
 */

namespace MoceanTest\NumberLookup;

use MoceanTest\AbstractTesting;

class NumberLookupTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('number_lookup.json');
        $this->mockXmlResponseStr = $this->getResponseString('number_lookup.xml');

        $this->jsonResponse = \Mocean\NumberLookup\NumberLookup::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\NumberLookup\NumberLookup::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-to'          => 'testing to',
            'mocean-nl-url'      => 'testing nl url',
        ];

        $req = new \Mocean\NumberLookup\NumberLookup('testing to', $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\NumberLookup\NumberLookup('testing to');
        $setterReq->setTo('testing to');
        $setterReq->setResponseFormat('json');
        $setterReq->setNlUrl('testing nl url');

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

    public function testDirectAccessResponseData()
    {
        $this->objectTesting($this->jsonResponse);
        $this->objectTesting($this->xmlResponse);
    }

    public function testObjectErrorWhenCreateFromResponseWithStatus0()
    {
        try {
            \Mocean\NumberLookup\NumberLookup::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    private function objectTesting($res)
    {
        $this->assertEquals($res->status, '0');
        $this->assertEquals($res->msgid, 'CPASS_restapi_C00000000000000.0002');
        $this->assertEquals($res->to, '60123456789');
        $this->assertEquals($res->ported, 'ported');
        $this->assertEquals($res->reachable, 'reachable');
        $this->assertEquals($res->current_carrier->country, 'MY');
        $this->assertEquals($res->current_carrier->name, 'U Mobile');
        $this->assertEquals($res->current_carrier->network_code, '50218');
        $this->assertEquals($res->current_carrier->mcc, '502');
        $this->assertEquals($res->current_carrier->mnc, '18');
        $this->assertEquals($res->original_carrier->country, 'MY');
        $this->assertEquals($res->original_carrier->name, 'Maxis Mobile');
        $this->assertEquals($res->original_carrier->network_code, '50212');
        $this->assertEquals($res->original_carrier->mcc, '502');
        $this->assertEquals($res->original_carrier->mnc, '12');

        $this->assertEquals($res['status'], '0');
        $this->assertEquals($res['msgid'], 'CPASS_restapi_C00000000000000.0002');
        $this->assertEquals($res['to'], '60123456789');
        $this->assertEquals($res['ported'], 'ported');
        $this->assertEquals($res['reachable'], 'reachable');
        $this->assertEquals($res['current_carrier']['country'], 'MY');
        $this->assertEquals($res['current_carrier']['name'], 'U Mobile');
        $this->assertEquals($res['current_carrier']['network_code'], '50218');
        $this->assertEquals($res['current_carrier']['mcc'], '502');
        $this->assertEquals($res['current_carrier']['mnc'], '18');
        $this->assertEquals($res['original_carrier']['country'], 'MY');
        $this->assertEquals($res['original_carrier']['name'], 'Maxis Mobile');
        $this->assertEquals($res['original_carrier']['network_code'], '50212');
        $this->assertEquals($res['original_carrier']['mcc'], '502');
        $this->assertEquals($res['original_carrier']['mnc'], '12');
    }
}
