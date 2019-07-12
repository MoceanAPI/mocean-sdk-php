<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 11:02 AM.
 */

namespace MoceanTest\Voice;


use MoceanTest\AbstractTesting;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

class ClientTest extends AbstractTesting
{
    /** @var \Mocean\Client $moceanClient */
    protected $moceanClient;

    protected $mockMoceanClient;
    /** @var \Mocean\Voice\Client $mockVoiceClient */
    protected $mockVoiceClient;

    public function setUp()
    {
        $this->moceanClient = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $this->mockMoceanClient = $this->prophesize('Mocean\Client');
        $this->mockVoiceClient = new \Mocean\Voice\Client();
        $this->mockVoiceClient->setClient($this->mockMoceanClient->reveal());
    }

    public function testMakeCall()
    {
        $inputParams = [
            'mocean-to'   => 'testing to'
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('/voice/dial', $request->getUri()->getPath());

            $queryArr = $this->convertArrayFromQueryString($request->getUri()->getQuery());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('voice.xml'));

        $voiceRes = $this->mockVoiceClient->call($inputParams);
        $this->assertInstanceOf(\Mocean\Voice\Voice::class, $voiceRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCallParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->mockVoiceClient->call('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testCallRequiredRequestParamNotPresent()
    {
        $this->mockVoiceClient->call([]);
    }

    public function testResponseDataIsEmpty()
    {
        $this->mockMoceanClient->send(Argument::that(function () {
            return true;
        }))->shouldBeCalledTimes(1)->willReturn(new Response());

        try {
            $this->mockVoiceClient->call(['mocean-to' => 'testing to']);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }
}
