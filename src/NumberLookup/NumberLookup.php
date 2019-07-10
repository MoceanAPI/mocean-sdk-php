<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 4/8/2019
 * Time: 9:45 AM.
 */

namespace Mocean\NumberLookup;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class NumberLookup implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];

    /**
     * NumberLookup constructor.
     *
     * @param string $to
     * @param array  $extra
     */
    public function __construct($to, $extra = [])
    {
        $this->requestData['mocean-to'] = $to;
        $this->requestData = array_merge($this->requestData, $extra);
    }

    public function setTo($to)
    {
        $this->requestData['mocean-to'] = $to;

        return $this;
    }

    public function setNlUrl($url)
    {
        $this->requestData['mocean-nl-url'] = $url;

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

    public static function createFromResponse($responseData, $version)
    {
        $numberLookup = new self(null);
        $numberLookup->setRawResponseData($responseData)
            ->processResponse($version);

        if ($numberLookup['status'] !== 0 && $numberLookup['status'] !== '0') {
            throw new Exception($numberLookup['err_msg']);
        }

        return $numberLookup;
    }
}
