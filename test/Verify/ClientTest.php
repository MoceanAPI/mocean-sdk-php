<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:31 PM.
 */

namespace MoceanTest\Verify;

use Mocean\Client\Exception\Exception;
use Mocean\Verify\Channel;
use Mocean\Verify\SendCode;
use MoceanTest\AbstractTesting;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class ClientTest extends AbstractTesting
{
    /** @var \Mocean\Client $moceanClient */
    protected $moceanClient;

    protected $mockMoceanClient;
    /** @var \Mocean\Verify\Client $mockVerifyClient */
    protected $mockVerifyClient;

    public function setUp()
    {
        $this->moceanClient = new \Mocean\Client(new \Mocean\Client\Credentials\Basic($this->apiKey, $this->apiSecret));

        $this->mockMoceanClient = $this->prophesize('Mocean\Client');
        $this->mockVerifyClient = new \Mocean\Verify\Client();
        $this->mockVerifyClient->setClient($this->mockMoceanClient->reveal());
    }

    public function testSendCode()
    {
        $inputParams = [
            'mocean-to'    => 'testing to',
            'mocean-brand' => 'testing brand',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('/verify/req', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $queryArr['mocean-brand']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('send_code.xml'));

        $sendCodeRes = $this->mockVerifyClient->start($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);
    }

    public function testSendCodeAsSmsChannel()
    {
        $inputParams = [
            'mocean-to' => 'testing to',
            'mocean-brand' => 'testing brand',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('/verify/req/sms', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $queryArr['mocean-brand']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('send_code.xml'));

        $sendCodeRes = $this->mockVerifyClient->sendAs(Channel::SMS)->start($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);
    }

    public function testResendCode()
    {
        $inputParams = [
            'mocean-reqid' => 'CPASS_restapi_C0000002737000000.0002',
        ];

        $sendCodeRes = SendCode::createFromResponse($this->getResponseString('send_code.xml'), $this->defaultVersion);
        $sendCodeRes->setClient($this->mockVerifyClient);

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('/verify/resend/sms', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-reqid'], $queryArr['mocean-reqid']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('resend_code.xml'));

        $resendCodeRes = $sendCodeRes->resend();
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $resendCodeRes);
    }

    public function testVerifyCode()
    {
        $inputParams = [
            'mocean-reqid' => 'testing reqid',
            'mocean-code'  => 'testing code',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('/verify/check', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-reqid'], $queryArr['mocean-reqid']);
            $this->assertEquals($inputParams['mocean-code'], $queryArr['mocean-code']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('verify_code.xml'));

        $verifyCodeRes = $this->mockVerifyClient->check($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\VerifyCode::class, $verifyCodeRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testStartParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->mockVerifyClient->start('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCheckParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->mockVerifyClient->check('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testStartRequiredRequestParamNotPresent()
    {
        $this->mockVerifyClient->start([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-reqid`
     */
    public function testCheckRequiredRequestParamNotPresent()
    {
        $this->mockVerifyClient->check([]);
    }
}
