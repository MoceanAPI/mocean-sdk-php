<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 3:24 PM.
 */

namespace MoceanTest\Account;

use MoceanTest\AbstractTesting;

class ClientTest extends AbstractTesting
{
    public function testGetBalance()
    {
        $this->interceptRequest('balance.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $balanceRes = $client->account()->getBalance();
            $this->assertInstanceOf(\Mocean\Account\Balance::class, $balanceRes);

            $this->assertEquals('GET', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/account/balance'), $httpClient->getLastRequest()->getUri()->getPath());
        });
    }

    public function testGetPrice()
    {
        $this->interceptRequest('price.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $priceRes = $client->account()->getPricing();
            $this->assertInstanceOf(\Mocean\Account\Price::class, $priceRes);

            $this->assertEquals('GET', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/account/pricing'), $httpClient->getLastRequest()->getUri()->getPath());
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetBalanceParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->account()->getBalance('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetPricingParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->account()->getPricing('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    public function testResponseDataIsEmpty()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client) {
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
        });
    }
}
