<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 12:43 PM.
 */

namespace MoceanTest;

class MoceanClientTest extends AbstractTesting
{
    public function testCreateMoceanClientUsingBasicCredentials()
    {
        $moceanClient = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $crendentialObject = $this->getClass(\Mocean\Client::class, 'credentials', $moceanClient);
        $crendentialData = $this->getClass(\Mocean\Client\Credentials\Basic::class, 'credentials', $crendentialObject);

        $this->assertInstanceOf(\Mocean\Client::class, $moceanClient);
        $this->assertInstanceOf(\Mocean\Client\Credentials\Basic::class, $crendentialObject);
        $this->assertEquals(['mocean-api-key' => $this->apiKey, 'mocean-api-secret' => $this->apiSecret], $crendentialData);
    }

    public function testMultipleMoceanClient()
    {
        $moceanClient1 = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));
        $moceanClient2 = new \Mocean\Client(new \Mocean\Client\Credentials\Basic('test_api_key_2', 'test_api_secret_2'));

        $this->assertNotSame($moceanClient1, $moceanClient2);
    }

    public function testFactoryFunction()
    {
        $api = $this->prophesize('stdClass')->reveal();

        $factory = $this->prophesize('Mocean\Client\Factory\FactoryInterface');
        $factory->hasApi('message')->willReturn(true);
        $factory->getApi('message')->willReturn($api);

        $moceanClient = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));
        $moceanClient->setFactory($factory->reveal());

        $this->assertSame($api, $moceanClient->message());
        $this->assertSame($api, $moceanClient->message);
    }
}
