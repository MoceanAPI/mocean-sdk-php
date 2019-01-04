<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 10:27 AM.
 */

namespace Mocean\Account;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class Balance implements ModelInterface
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];

    /**
     * Balance constructor.
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
     * @return Balance
     */
    public static function createFromResponse($responseData)
    {
        $balance = new self();
        $balance->setRawResponseData($responseData)
            ->processResponse();

        if ($balance['status'] !== 0 && $balance['status'] !== '0') {
            throw new Exception($balance['err_msg']);
        }

        return $balance;
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
