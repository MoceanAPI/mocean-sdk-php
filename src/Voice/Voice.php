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
use Mocean\Voice\Mccc\AbstractMccc;

class Voice implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];

    /**
     * Voice constructor.
     *
     * @param $to
     * @param AbstractMccc|McccBuilder|array|null $mccc
     * @param array $extra
     */
    public function __construct($to, $mccc = null, $extra = [])
    {
        $this->requestData['mocean-to'] = $to;
        $this->requestData = array_merge($this->requestData, $extra);
        if ($mccc) {
            $this->setCallControlCommands($mccc);
        }
    }

    /**
     * @param $responseData
     * @param $version
     * @return Voice
     * @throws Exception
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

    public function setCallEventUrl($callEventUrl)
    {
        $this->requestData['mocean-call-event-url'] = $callEventUrl;

        return $this;
    }

    public function setCallControlCommands($callControlCommands)
    {
        if ($callControlCommands instanceof McccBuilder) {
            $this->requestData['mocean-call-control-commands'] = json_encode($callControlCommands->build(), JSON_UNESCAPED_UNICODE);
        } else if ($callControlCommands instanceof AbstractMccc) {
            $this->requestData['mocean-call-control-commands'] = json_encode([$callControlCommands->getRequestData()], JSON_UNESCAPED_UNICODE);
        } else if (is_array($callControlCommands)) {
            $this->requestData['mocean-call-control-commands'] = json_encode($callControlCommands, JSON_UNESCAPED_UNICODE);
        } else {
            $this->requestData['mocean-call-control-commands'] = $callControlCommands;
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
