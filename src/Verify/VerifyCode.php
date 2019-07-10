<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 5:14 PM.
 */

namespace Mocean\Verify;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class VerifyCode implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];

    /**
     * Balance constructor.
     *
     * @param array $extra
     */
    public function __construct($reqId, $code, $extra = [])
    {
        $this->requestData['mocean-reqid'] = $reqId;
        $this->requestData['mocean-code'] = $code;
        $this->requestData = array_merge($this->requestData, $extra);
    }

    /**
     * @param $responseData
     *
     * @throws Exception
     *
     * @return VerifyCode
     */
    public static function createFromResponse($responseData, $version)
    {
        $verifyCode = new self(null, null);
        $verifyCode->setRawResponseData($responseData)
            ->processResponse($version);

        if ($verifyCode['status'] !== 0 && $verifyCode['status'] !== '0') {
            throw new Exception($verifyCode['err_msg']);
        }

        return $verifyCode;
    }

    public function setReqId($reqId)
    {
        $this->requestData['mocean-reqid'] = $reqId;

        return $this;
    }

    public function setCode($code)
    {
        $this->requestData['mocean-code'] = $code;

        return $this;
    }

    public function setResponseFormat($responseFormat)
    {
        $this->requestData['mocean-resp-format'] = $responseFormat;

        return $this;
    }

    public function getRequestData()
    {
        return $this->requestData;
    }
}
