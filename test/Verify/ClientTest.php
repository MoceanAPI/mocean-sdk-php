<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:31 PM.
 */

namespace MoceanTest\Verify;

use Mocean\Verify\Channel;
use Mocean\Verify\SendCode;
use MoceanTest\AbstractTesting;

class ClientTest extends AbstractTesting
{
    public function testSendCode()
    {
        $this->interceptRequest('send_code.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-to'    => 'testing to',
                'mocean-brand' => 'testing brand',
            ];

            $sendCodeRes = $client->verify()->start($inputParams);
            $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/verify/req'), $httpClient->getLastRequest()->getUri()->getPath());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $queryArr['mocean-brand']);
        });
    }

    public function testSendCodeAsSmsChannel()
    {
        $this->interceptRequest('send_code.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-to'    => 'testing to',
                'mocean-brand' => 'testing brand',
            ];

            $sendCodeRes = $client->verify()->sendAs(Channel::SMS)->start($inputParams);
            $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/verify/req/sms'), $httpClient->getLastRequest()->getUri()->getPath());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $queryArr['mocean-brand']);
        });
    }

    public function testResendCode()
    {
        $this->interceptRequest('resend_code.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-reqid' => 'CPASS_restapi_C0000002737000000.0002',
            ];

            $sendCodeRes = SendCode::createFromResponse($this->getResponseString('send_code.xml'), $this->defaultVersion);
            $sendCodeRes->setClient($client->verify());

            $resendCodeRes = $sendCodeRes->resend();
            $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $resendCodeRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/verify/resend/sms'), $httpClient->getLastRequest()->getUri()->getPath());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-reqid'], $queryArr['mocean-reqid']);
        });
    }

    public function testVerifyCode()
    {
        $this->interceptRequest('verify_code.xml', function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $inputParams = [
                'mocean-reqid' => 'testing reqid',
                'mocean-code'  => 'testing code',
            ];

            $verifyCodeRes = $client->verify()->check($inputParams);
            $this->assertInstanceOf(\Mocean\Verify\VerifyCode::class, $verifyCodeRes);

            $this->assertEquals('POST', $httpClient->getLastRequest()->getMethod());
            $this->assertEquals($this->getTestUri('/verify/check'), $httpClient->getLastRequest()->getUri()->getPath());
            $httpClient->getLastRequest()->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($httpClient->getLastRequest()->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-reqid'], $queryArr['mocean-reqid']);
            $this->assertEquals($inputParams['mocean-code'], $queryArr['mocean-code']);
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testStartParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->verify()->start('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCheckParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->verify()->check('inputString');

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testStartRequiredRequestParamNotPresent()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->verify()->start([]);

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-reqid`
     */
    public function testCheckRequiredRequestParamNotPresent()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client, \Http\Mock\Client $httpClient) {
            $client->verify()->check([]);

            $this->assertFalse($httpClient->getLastRequest());
        });
    }

    public function testResponseDataIsEmpty()
    {
        $this->interceptRequest(null, function (\Mocean\Client $client) {
            try {
                $client->verify()->start([
                    'mocean-to'    => 'testing to',
                    'mocean-brand' => 'testing brand',
                ]);
                $this->fail();
            } catch (\Mocean\Client\Exception\Exception $e) {
            }

            try {
                $client->verify()->check([
                    'mocean-reqid' => 'testing reqid',
                    'mocean-code'  => 'testing code',
                ]);
                $this->fail();
            } catch (\Mocean\Client\Exception\Exception $e) {
            }
        });
    }
}
