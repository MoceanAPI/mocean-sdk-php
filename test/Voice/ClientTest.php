<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 11:02 AM.
 */

namespace MoceanTest\Voice;

use GuzzleHttp\Psr7\Response;
use Mocean\Voice\Mc;
use MoceanTest\AbstractTesting;
use Psr\Http\Message\RequestInterface;

class ClientTest extends AbstractTesting
{
    public function testMakeCall()
    {
        $inputParams = [
            'mocean-to'      => 'testing to',
            'mocean-command' => Mc::say('hello world'),
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/voice/dial'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-to'], $body['mocean-to']);
            $this->assertEquals(Mc::say('hello world')->getRequestData(), json_decode($body['mocean-command'], true)[0]);

            return $this->getResponse('voice.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $voiceRes = $client->voice()->call($inputParams);
        $this->assertInstanceOf(\Mocean\Voice\Voice::class, $voiceRes);
    }

    public function testHangUp()
    {
        $callUuid = 'xxx-xxx-xxx-xxx';

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($callUuid) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/voice/hangup'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($callUuid, $body['mocean-call-uuid']);

            return $this->getResponse('hangup.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $hangupRes = $client->voice()->hangup($callUuid);
        $this->assertInstanceOf(\Mocean\Voice\Voice::class, $hangupRes);
    }

    public function testRecording()
    {
        $callUuid = 'xxx-xxx-xxx-xxx';

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($callUuid) {
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals($this->getTestUri('/voice/rec'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($callUuid, $body['mocean-call-uuid']);

            return new Response(200, ['Content-Type' => 'audio/mpeg'], null);
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $recordingRes = $client->voice()->recording($callUuid);
        $this->assertInstanceOf(\Mocean\Voice\Recording::class, $recordingRes);
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testRecordingWithEmptyBody()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->voice()->recording('xxx-xxx-xxx-xxx');
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testRecordingWithErrorResponse()
    {
        $mockHttp = $this->makeMockHttpClient($this->getResponse('error_response.json'));

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $client->voice()->recording('xxx-xxx-xxx-xxx');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCallParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->voice()->call('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testCallRequiredRequestParamNotPresent()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->voice()->call([]);
    }

    public function testResponseDataIsEmpty()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        try {
            $client->voice()->call(['mocean-to' => 'testing to']);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }

        try {
            $client->voice()->hangup('xxx-xxx-xxx-xxx');
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }

        try {
            $client->voice()->recording('xxx-xxx-xxx-xxx');
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }
}
