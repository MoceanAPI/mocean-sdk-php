<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:31 PM.
 */

namespace MoceanTest\NumberLookup;

use MoceanTest\AbstractTesting;

class ClientTest extends AbstractTesting
{
    public function testNumberLookup()
    {
        $this->interceptRequest('number_lookup.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-to' => 'testing to',
            ];

            $numberLookupRes = $client->numberLookup()->inquiry($inputParams);
            $this->assertInstanceOf(\Mocean\NumberLookup\NumberLookup::class, $numberLookupRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/nl'), $httpClient->getLastRequest()->getUri()->getPath());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSendParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->numberLookup()->inquiry('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testSendRequiredRequestParamNotPresent()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->numberLookup()->inquiry([]);

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    public function testResponseDataIsEmpty()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client) {
            try {
                $client->numberLookup()->inquiry(['mocean-to' => 'testing to']);
                $this->fail();
            } catch (\Mocean\Client\Exception\Exception $e) {
            }
        });
    }
}
