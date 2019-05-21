<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:24 PM.
 */

namespace MoceanTest\Account;

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
    /** @var \Mocean\Account\Client $mockAccountClient */
    protected $mockAccountClient;

    public function setUp()
    {
        $this->moceanClient = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $this->mockMoceanClient = $this->prophesize('Mocean\Client');
        $this->mockAccountClient = new \Mocean\Account\Client();
        $this->mockAccountClient->setClient($this->mockMoceanClient->reveal());
    }

    public function testGetBalance()
    {
        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('/account/balance', $request->getUri()->getPath());

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__.'/responses/balance.xml'));

        $balanceRes = $this->mockAccountClient->getBalance();
        $this->assertInstanceOf(\Mocean\Account\Balance::class, $balanceRes);
    }

    public function testGetPrice()
    {
        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('/account/pricing', $request->getUri()->getPath());

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__.'/responses/price.xml'));

        $priceRes = $this->mockAccountClient->getPricing();
        $this->assertInstanceOf(\Mocean\Account\Price::class, $priceRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetBalanceParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->moceanClient->account()->getBalance('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetPricingParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->moceanClient->account()->getPricing('inputString');
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testGetBalanceIfTheresErrorResponse()
    {
        $this->moceanClient->account()->getBalance();
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testGetPricingIfTheresErrorResponse()
    {
        $this->moceanClient->account()->getPricing();
    }
}
