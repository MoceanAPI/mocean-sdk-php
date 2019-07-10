<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 5:02 PM.
 */

namespace Mocean\Verify;

use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class SendCode implements ModelInterface, ClientAwareInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait, ClientAwareTrait;

    protected $requestData = [];

    /**
     * SendCode constructor.
     *
     * @param $to
     * @param $brand
     * @param array $extra
     */
    public function __construct($to = null, $brand = null, $extra = [])
    {
        $this->requestData['mocean-to'] = $to;
        $this->requestData['mocean-brand'] = $brand;
        $this->requestData = array_merge($this->requestData, $extra);
    }

    /**
     * @param $responseData
     *
     * @throws Exception
     *
     * @return SendCode
     */
    public static function createFromResponse($responseData, $version)
    {
        $sendCode = new self(null, null);
        $sendCode->setRawResponseData($responseData)
            ->processResponse($version);

        if ($sendCode['status'] !== 0 && $sendCode['status'] !== '0') {
            throw new Exception($sendCode['err_msg']);
        }

        return $sendCode;
    }

    public function setTo($to)
    {
        $this->requestData['mocean-to'] = $to;

        return $this;
    }

    public function setBrand($brand)
    {
        $this->requestData['mocean-brand'] = $brand;

        return $this;
    }

    public function setFrom($from)
    {
        $this->requestData['mocean-from'] = $from;

        return $this;
    }

    public function setCodeLength($codeLength)
    {
        $this->requestData['mocean-code-length'] = $codeLength;

        return $this;
    }

    public function setPinValidity($pinValidity)
    {
        $this->requestData['mocean-pin-validity'] = $pinValidity;

        return $this;
    }

    public function setNextEventWait($nextEventWait)
    {
        $this->requestData['mocean-next-event-wait'] = $nextEventWait;

        return $this;
    }

    public function setResponseFormat($responseFormat)
    {
        $this->requestData['mocean-resp-format'] = $responseFormat;

        return $this;
    }

    public function resend()
    {
        if (!$this->reqid) {
            throw new Exception('reqid not available due to failed request or this is not a response object');
        }

        /** @var Client $client */
        $client = $this->getClient();

        return $client->start([
            'mocean-reqid' => $this->reqid,
        ], true);
    }

    public function getRequestData()
    {
        return $this->requestData;
    }
}
