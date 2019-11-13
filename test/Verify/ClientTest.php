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
use Psr\Http\Message\RequestInterface;

class ClientTest extends AbstractTesting
{
    public function testSendCode()
    {
        $inputParams = [
            'mocean-to'    => 'testing to',
            'mocean-brand' => 'testing brand',
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/verify/req'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-to'], $body['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $body['mocean-brand']);

            return $this->getResponse('send_code.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $sendCodeRes = $client->verify()->start($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);
    }

    public function testSendCodeAsSmsChannel()
    {
        $inputParams = [
            'mocean-to'    => 'testing to',
            'mocean-brand' => 'testing brand',
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/verify/req/sms'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-to'], $body['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $body['mocean-brand']);

            return $this->getResponse('send_code.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $sendCodeRes = $client->verify()->sendAs(Channel::SMS)->start($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);
    }

    public function testResendCode()
    {
        $inputParams = [
            'mocean-reqid' => 'CPASS_restapi_C0000002737000000.0002',
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/verify/resend/sms'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-reqid'], $body['mocean-reqid']);

            return $this->getResponse('resend_code.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $sendCodeRes = SendCode::createFromResponse($this->getResponseString('send_code.xml'), $this->defaultVersion);
        $sendCodeRes->setClient($client->verify());

        $resendCodeRes = $sendCodeRes->resend();
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $resendCodeRes);
    }

    public function testVerifyCode()
    {
        $inputParams = [
            'mocean-reqid' => 'testing reqid',
            'mocean-code'  => 'testing code',
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/verify/check'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-reqid'], $body['mocean-reqid']);
            $this->assertEquals($inputParams['mocean-code'], $body['mocean-code']);

            return $this->getResponse('verify_code.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $verifyCodeRes = $client->verify()->check($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\VerifyCode::class, $verifyCodeRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testStartParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->verify()->start('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCheckParamsNotImplementModelInterfaceAndNotArray()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->verify()->check('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testStartRequiredRequestParamNotPresent()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->verify()->start([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-reqid`
     */
    public function testCheckRequiredRequestParamNotPresent()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

        $client->verify()->check([]);
    }

    public function testResponseDataIsEmpty()
    {
        $client = $this->makeMoceanClientWithEmptyResponse();

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
    }
}
