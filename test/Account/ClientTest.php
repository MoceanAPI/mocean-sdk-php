<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:24 PM.
 */

namespace MoceanTest\Account;

use MoceanTest\AbstractTesting;
use Psr\Http\Message\RequestInterface;

class ClientTest extends AbstractTesting
{
    public function testGetBalance()
    {
        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals($this->getTestUri('/account/balance'), $request->getUri()->getPath());

            return $this->getResponse('balance.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $balanceRes = $client->account()->getBalance();
        $this->assertInstanceOf(\Mocean\Account\Balance::class, $balanceRes);
    }

    public function testGetPrice()
    {
        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals($this->getTestUri('/account/pricing'), $request->getUri()->getPath());

            return $this->getResponse('price.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $priceRes = $client->account()->getPricing();
        $this->assertInstanceOf(\Mocean\Account\Price::class, $priceRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetBalanceParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->account()->getBalance('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetPricingParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->account()->getPricing('inputString');
    }

    public function testResponseDataIsEmpty()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        try {
            $client->account()->getPricing();
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }

        try {
            $client->account()->getBalance();
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }
}
