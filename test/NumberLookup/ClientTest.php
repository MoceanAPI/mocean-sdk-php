<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:31 PM.
 */

namespace MoceanTest\NumberLookup;

use MoceanTest\AbstractTesting;
use Psr\Http\Message\RequestInterface;

class ClientTest extends AbstractTesting
{
    public function testNumberLookup()
    {
        $inputParams = [
            'mocean-to' => 'testing to',
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/nl'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-to'], $body['mocean-to']);

            return $this->getResponse('number_lookup.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $numberLookupRes = $client->numberLookup()->inquiry($inputParams);
        $this->assertInstanceOf(\Mocean\NumberLookup\NumberLookup::class, $numberLookupRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSendParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->numberLookup()->inquiry('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testSendRequiredRequestParamNotPresent()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->numberLookup()->inquiry([]);
    }

    public function testResponseDataIsEmpty()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        try {
            $client->numberLookup()->inquiry(['mocean-to' => 'testing to']);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }
}
