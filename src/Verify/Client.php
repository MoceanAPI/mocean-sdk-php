<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Verify;

use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;
use Zend\Diactoros\Request;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    protected $verifyChargeType = ChargeType::CHARGE_PER_CONVERSION;

    public function sendAs($chargeType = ChargeType::CHARGE_PER_CONVERSION)
    {
        $this->verifyChargeType = $chargeType;
        return $this;
    }

    public function start($verification)
    {
        if (!($verification instanceof ModelInterface)) {
            if (!\is_array($verification)) {
                throw new \RuntimeException('send code must implement `'.ModelInterface::class.'` or be an array`');
            }

            foreach (['mocean-to', 'mocean-brand'] as $param) {
                if (!isset($verification[$param])) {
                    throw new \InvalidArgumentException('missing expected key `'.$param.'`');
                }
            }

            $to = $verification['mocean-to'];
            $brand = $verification['mocean-brand'];
            unset($verification['mocean-to'], $verification['mocean-brand']);
            $verification = new SendCode($to, $brand, $verification);
        }

        $params = $verification->getRequestData();

        $verifyRequestUrl = \Mocean\Client::BASE_REST . '/verify/req';
        if ($this->verifyChargeType === ChargeType::CHARGE_PER_ATTEMPT) {
            $verifyRequestUrl .= '/sms';
        }

        $request = new Request(
            $verifyRequestUrl,
            'POST',
            'php://temp',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
        $response = $this->client->send($request);
        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();
        if (!isset($data)) {
            throw new Exception\Exception('unexpected response from API');
        }

        return SendCode::createFromResponse($data);
    }

    public function check($verification)
    {
        if (!($verification instanceof ModelInterface)) {
            if (!\is_array($verification)) {
                throw new \RuntimeException('verify code must implement `'.ModelInterface::class.'` or be an array`');
            }

            foreach (['mocean-reqid', 'mocean-code'] as $param) {
                if (!isset($verification[$param])) {
                    throw new \InvalidArgumentException('missing expected key `'.$param.'`');
                }
            }

            $reqId = $verification['mocean-reqid'];
            $code = $verification['mocean-code'];
            unset($verification['mocean-reqid'], $verification['mocean-code']);
            $verification = new VerifyCode($reqId, $code, $verification);
        }

        $params = $verification->getRequestData();

        $request = new Request(
            \Mocean\Client::BASE_REST.'/verify/check',
            'POST',
            'php://temp',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
        $response = $this->client->send($request);
        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data)) {
            throw new Exception\Exception('unexpected response from API');
        }

        return VerifyCode::createFromResponse($data);
    }
}
