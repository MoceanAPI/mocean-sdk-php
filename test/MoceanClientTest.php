<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 12:43 PM.
 */

namespace MoceanTest;

use GuzzleHttp\Psr7\Request;

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

    /**
     * @expectedException \RuntimeException
     */
    public function testMoceanCreateWithObjectOtherThanCredentialsInterface()
    {
        new \Mocean\Client(new DummyCredentials());
    }

    public function testMoceanCreateWithCustomOptions()
    {
        $mocean = new \Mocean\Client(
            new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret),
            [
                'baseUrl' => 'https://dummy.com',
                'version' => '1000',
            ]
        );
        $this->assertEquals('https://dummy.com', $mocean->baseUrl);
        $this->assertEquals('1000', $mocean->version);
    }

    public function testCustomHttpClient()
    {
        $client = new \Http\Adapter\Guzzle6\Client();

        $mocean = new \Mocean\Client(
            new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret),
            [],
            $client
        );

        $this->assertSame($client, $mocean->getHttpClient());
    }

    public function testXWWWFormUrlAuthRequest()
    {
        $request = new Request(
            'POST',
            'https://simplyUrl',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query(['test' => 'test value']));

        $processedRequest = \Mocean\Client::authRequest($request, new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $body = $processedRequest->getBody();
        $body->rewind();
        $content = $body->getContents();
        parse_str($content, $params);

        $this->assertEquals($this->apiKey, $params['mocean-api-key']);
        $this->assertEquals($this->apiSecret, $params['mocean-api-secret']);
        $this->assertEquals('test value', $params['test']);
    }

    public function testJsonAuthRequest()
    {
        $request = new Request(
            'POST',
            'https://simplyUrl',
            ['content-type' => 'application/json']
        );

        $request->getBody()->write(json_encode(['test' => 'test value']));

        $processedRequest = \Mocean\Client::authRequest($request, new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $body = $processedRequest->getBody();
        $body->rewind();
        $content = $body->getContents();
        $params = json_decode($content, true);

        $this->assertEquals($this->apiKey, $params['mocean-api-key']);
        $this->assertEquals($this->apiSecret, $params['mocean-api-secret']);
        $this->assertEquals('test value', $params['test']);
    }

    public function testGetQueryAuthRequest()
    {
        $request = new Request(
            'GET',
            'https://simplyUrl?'.http_build_query(['test' => 'test value'])
        );

        $processedRequest = \Mocean\Client::authRequest($request, new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        parse_str($processedRequest->getUri()->getQuery(), $params);

        $this->assertEquals($this->apiKey, $params['mocean-api-key']);
        $this->assertEquals($this->apiSecret, $params['mocean-api-secret']);
        $this->assertEquals('test value', $params['test']);
    }
}
