<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:31 PM.
 */

namespace MoceanTest\NumberLookup;

use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class ClientTesting extends AbstractTesting
{
    use ResponseTrait;

    /** @var \Mocean\Client $moceanClient */
    protected $moceanClient;

    protected $mockMoceanClient;
    /** @var \Mocean\NumberLookup\Client $mockNumberLookupClient */
    protected $mockNumberLookupClient;

    public function setUp()
    {
        $this->moceanClient = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $this->mockMoceanClient = $this->prophesize('Mocean\Client');
        $this->mockNumberLookupClient = new \Mocean\NumberLookup\Client();
        $this->mockNumberLookupClient->setClient($this->mockMoceanClient->reveal());
    }

    public function testNumberLookup()
    {
        $inputParams = [
            'mocean-to'    => 'testing to',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('rest.moceanapi.com', $request->getUri()->getHost());
            $this->assertEquals('/rest/1/nl', $request->getUri()->getPath());

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__.'/responses/number_lookup.xml'));

        $numberLookupRes = $this->mockNumberLookupClient->inquiry($inputParams);
        $this->assertInstanceOf(\Mocean\NumberLookup\NumberLookup::class, $numberLookupRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSendParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->moceanClient->numberLookup()->inquiry('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testSendRequiredRequestParamNotPresent()
    {
        $this->moceanClient->numberLookup()->inquiry([]);
    }
}
