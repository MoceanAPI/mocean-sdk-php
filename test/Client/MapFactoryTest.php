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
}
