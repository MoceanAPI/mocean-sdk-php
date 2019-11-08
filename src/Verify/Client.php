<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Verify;

use GuzzleHttp\Psr7\Request;
use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public $channel = Channel::AUTO;

    public function sendAs($channel = Channel::AUTO)
    {
        $this->channel = $channel;

        return $this;
    }

    public function start($verification, $isResend = false)
    {
        if (!($verification instanceof ModelInterface)) {
            if (!\is_array($verification)) {
                throw new \RuntimeException('send code must implement `'.ModelInterface::class.'` or be an array`');
            }

            if ($isResend) {
                $requiredKey = ['mocean-reqid'];
            } else {
                $requiredKey = ['mocean-to', 'mocean-brand'];
            }

            foreach ($requiredKey as $param) {
                if (!isset($verification[$param])) {
                    throw new \InvalidArgumentException('missing expected key `'.$param.'`');
                }
            }

            if ($isResend) {
                $verification = new SendCode(null, null, $verification);
            } else {
                $to = $verification['mocean-to'];
                $brand = $verification['mocean-brand'];
                unset($verification['mocean-to'], $verification['mocean-brand']);
                $verification = new SendCode($to, $brand, $verification);
            }
        }

        $params = $verification->getRequestData();

        if ($isResend) {
            $this->channel = Channel::SMS;
            $verifyRequestUrl = $this->buildUriByChannel('/verify/resend');
        } else {
            $verifyRequestUrl = $this->buildUriByChannel('/verify/req');
        }

        $request = new Request(
            'POST',
            $verifyRequestUrl,
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
        $response = $this->client->send($request);
        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        $sendCodeRes = SendCode::createFromResponse($data, $this->client->version);
        $sendCodeRes->setClient($this);

        return $sendCodeRes;
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
            'POST',
            '/verify/check',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
        $response = $this->client->send($request);
        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return VerifyCode::createFromResponse($data, $this->client->version);
    }

    protected function buildUriByChannel($url)
    {
        if ($this->channel === Channel::SMS) {
            return $url.'/sms';
        }

        return $url;
    }
}
