<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 10:40 AM.
 */

namespace Mocean\Voice;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;
use Mocean\Voice\Mc\AbstractMc;

class Voice implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];

    /**
     * Voice constructor.
     *
     * @param $to
     * @param AbstractMc|McBuilder|array|null $mc
     * @param array                           $extra
     */
    public function __construct($to, $mc = null, $extra = [])
    {
        $this->requestData['mocean-to'] = $to;
        $this->requestData = array_merge($this->requestData, $extra);
        if ($mc) {
            $this->setMoceanCommand($mc);
        }
    }

    /**
     * @param $responseData
     * @param $version
     *
     * @throws Exception
     *
     * @return Voice
     */
    public static function createFromResponse($responseData, $version)
    {
        $voice = new self(null, null);
        $voice->setRawResponseData($responseData)
            ->processResponse($version);

        if (isset($voice['status']) && $voice['status'] !== 0 && $voice['status'] !== '0') {
            throw new Exception($voice['err_msg']);
        }

        return $voice;
    }

    public function setTo($to)
    {
        $this->requestData['mocean-to'] = $to;

        return $this;
    }

    public function setEventUrl($eventUrl)
    {
        $this->requestData['mocean-event-url'] = $eventUrl;

        return $this;
    }

    public function setMoceanCommand($moceanCommand)
    {
        if ($moceanCommand instanceof McBuilder) {
            $this->requestData['mocean-command'] = json_encode($moceanCommand->build(), JSON_UNESCAPED_UNICODE);
        } elseif ($moceanCommand instanceof AbstractMc) {
            $this->requestData['mocean-command'] = json_encode([$moceanCommand->getRequestData()], JSON_UNESCAPED_UNICODE);
        } elseif (is_array($moceanCommand)) {
            $this->requestData['mocean-command'] = json_encode($moceanCommand, JSON_UNESCAPED_UNICODE);
        } else {
            $this->requestData['mocean-command'] = $moceanCommand;
        }

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
