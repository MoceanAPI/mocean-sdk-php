<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 11:43 AM.
 */

namespace Mocean\Account;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class Price implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];

    /**
     * Price constructor.
     *
     * @param array $extra
     */
    public function __construct($extra = [])
    {
        $this->requestData = array_merge($this->requestData, $extra);
    }

    /**
     * @param $responseData
     *
     * @throws Exception
     *
     * @return Price
     */
    public static function createFromResponse($responseData, $version)
    {
        $price = new self();
        $price->setRawResponseData($responseData)
            ->processResponse($version);

        if ($price['status'] !== 0 && $price['status'] !== '0') {
            throw new Exception($price['err_msg']);
        }

        return $price;
    }

    public function setResponseFormat($responseFormat)
    {
        $this->requestData['mocean-resp-format'] = $responseFormat;

        return $this;
    }

    public function setMcc($mcc)
    {
        $this->requestData['mocean-mcc'] = $mcc;

        return $this;
    }

    public function setMnc($mnc)
    {
        $this->requestData['mocean-mnc'] = $mnc;

        return $this;
    }

    public function setDelimiter($delimiter)
    {
        $this->requestData['mocean-delimiter'] = $delimiter;

        return $this;
    }

    public function getRequestData()
    {
        return $this->requestData;
    }
}
