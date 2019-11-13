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

class ClientTest extends AbstractTesting
{
    public function testMakeCall()
    {
        $this->interceptRequest('send_code.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-to' => 'testing to',
                'mocean-command' => Mc::say('hello world')
            ];

            $voiceRes = $client->voice()->call($inputParams);
            $this->assertInstanceOf(\Mocean\Voice\Voice::class, $voiceRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/voice/dial'), $httpClient->getLastRequest()->getUri()->getPath());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals(Mc::say('hello world')->getRequestData(), json_decode($queryArr['mocean-command'], true)[0]);
        });
    }

    public function testHangUp()
    {
        $this->interceptRequest('send_code.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $callUuid = 'xxx-xxx-xxx-xxx';

            $voiceRes = $client->voice()->hangup($callUuid);
            $this->assertInstanceOf(\Mocean\Voice\Voice::class, $voiceRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($callUuid, $queryArr['mocean-call-uuid']);
            $this->assertEquals($this->getTestUri('/voice/hangup'), $httpClient->getLastRequest()->getUri()->getPath());
        });
    }

    public function testRecording()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $callUuid = 'xxx-xxx-xxx-xxx';

            $recordingRes = $client->voice()->recording($callUuid);
            $this->assertInstanceOf(\Mocean\Voice\Recording::class, $recordingRes);

            $this->assertEquals('GET', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/voice/rec'), $httpClient->getLastRequest()->getUri()->getPath());
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getUri()->getQuery());
            $this->assertEquals($callUuid, $queryArr['mocean-call-uuid']);
        }, new Response(200, ['Content-Type' => 'audio/mpeg'], null));
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testRecordingWithEmptyBody()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->voice()->recording('xxx-xxx-xxx-xxx');
        });
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testRecordingWithErrorResponse()
    {
        $this->interceptRequest('error_response.json', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->voice()->recording('xxx-xxx-xxx-xxx');
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCallParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->voice()->call('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testCallRequiredRequestParamNotPresent()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->voice()->call([]);

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    public function testResponseDataIsEmpty()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client) {
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
        });
    }
}
