<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 5:31 PM.
 */

namespace MoceanTest\Verify;

use Mocean\Verify\ChargeType;
use MoceanTest\AbstractTesting;
use MoceanTest\ResponseTrait;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class ClientTesting extends AbstractTesting
{
    use ResponseTrait;

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
            $this->assertEquals('rest.moceanapi.com', $request->getUri()->getHost());
            $this->assertEquals('/rest/1/verify/req', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $queryArr['mocean-brand']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__.'/responses/send_code.xml'));

        $sendCodeRes = $this->mockVerifyClient->start($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);
    }

    public function testSendCodeAsCPA()
    {
        $inputParams = [
            'mocean-to' => 'testing to',
            'mocean-brand' => 'testing brand',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('rest.moceanapi.com', $request->getUri()->getHost());
            $this->assertEquals('/rest/1/verify/req/sms', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-to'], $queryArr['mocean-to']);
            $this->assertEquals($inputParams['mocean-brand'], $queryArr['mocean-brand']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__ . '/responses/send_code.xml'));

        $sendCodeRes = $this->mockVerifyClient->sendAs(ChargeType::CHARGE_PER_ATTEMPT)->start($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\SendCode::class, $sendCodeRes);
    }

    public function testVerifyCode()
    {
        $inputParams = [
            'mocean-reqid' => 'testing reqid',
            'mocean-code'  => 'testing code',
        ];

        $this->mockMoceanClient->send(Argument::that(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals('rest.moceanapi.com', $request->getUri()->getHost());
            $this->assertEquals('/rest/1/verify/check', $request->getUri()->getPath());

            $request->getBody()->rewind();
            $queryArr = $this->convertArrayFromQueryString($request->getBody()->getContents());
            $this->assertEquals($inputParams['mocean-reqid'], $queryArr['mocean-reqid']);
            $this->assertEquals($inputParams['mocean-code'], $queryArr['mocean-code']);

            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse(__DIR__.'/responses/verify_code.xml'));

        $verifyCodeRes = $this->mockVerifyClient->check($inputParams);
        $this->assertInstanceOf(\Mocean\Verify\VerifyCode::class, $verifyCodeRes);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testStartParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->moceanClient->verify()->start('inputString');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCheckParamsNotImplementModelInterfaceAndNotArray()
    {
        $this->moceanClient->verify()->check('inputString');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-to`
     */
    public function testStartRequiredRequestParamNotPresent()
    {
        $this->moceanClient->verify()->start([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `mocean-reqid`
     */
    public function testCheckRequiredRequestParamNotPresent()
    {
        $this->moceanClient->verify()->check([]);
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testStartIfTheresErrorResponse()
    {
        $this->moceanClient->verify()->start([
            'mocean-to'    => 'testing to',
            'mocean-brand' => 'testing brand',
        ]);
    }

    /**
     * @expectedException \Mocean\Client\Exception\Exception
     */
    public function testCheckIfTheresErrorResponse()
    {
        $this->moceanClient->verify()->check([
            'mocean-reqid' => 'testing req id',
            'mocean-code'  => 'testing code',
        ]);
    }
}
