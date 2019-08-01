<?php

namespace MoceanTest\Client;

use Mocean\Client;
use Mocean\Client\Factory\MapFactory;
use PHPUnit\Framework\TestCase;

class MapFactoryTest extends TestCase
{
    /**
     * @var MapFactory
     */
    protected $factory;
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new Client(new Client\Credentials\Basic('test_api_key', 'test_api_secret'));
        $this->factory = new MapFactory([
            'test' => 'MoceanTest\Client\TempObject',
        ], $this->client);
    }

    public function testDependencyInjection()
    {
        $api = $this->factory->getApi('test');
        $this->assertTrue($this->factory->hasApi('test'));
        $this->assertSame($this->client, $api->getClient());
    }

    public function testCache()
    {
        $api = $this->factory->getApi('test');
        $cache = $this->factory->getApi('test');

        $this->assertSame($api, $cache);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetClientWithoutSettingClient()
    {
        (new \MoceanTest\Client\TempObject())->getClient();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetNonExistApiFromFactory()
    {
        $this->factory->getApi('dummy');
    }

    public function testCallExistApiWithProperty()
    {
        self::assertInstanceOf(\Mocean\Account\Client::class, $this->client->account);
    }

    public function testCallExistApiWithMethod()
    {
        self::assertInstanceOf(\Mocean\Account\Client::class, $this->client->account());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCallNonExistApiWithProperty()
    {
        $this->client->helloworld;
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCallNonExistApiWithMethod()
    {
        $this->client->helloworld();
    }
}
