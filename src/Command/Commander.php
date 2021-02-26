<?php

namespace Mocean\Command;

use Mocean\Client\Exception\Exception;
use Mocean\Command\Mc\AbstractMc;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class Commander implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];
    
    public function setEventUrl($eventUrl)
    {
        $this->requestData["mocean-event-url"] = $eventUrl;
        return $this;
    }

    public function setCommand($command)
    {
        $this->requestData['mocean-command'] = json_encode($command);
        return $this;
    }

    public function setResponseFormat($format)
    {
        $this->requestData['mocean-resp-format'] = $format;
    }

    public function getRequestData()
    {
        return $this->requestData;
    }

    public static function createFromResponse($responseData, $version)
    {
        $commander = new self(null, null);
        $commander->setRawResponseData($responseData)
            ->processResponse($version);

        if (isset($commander['status']) && $commander['status'] !== 0 && $commander['status'] !== '0') {
            throw new Exception($commander['err_msg']);
        }

        return $commander;
    }
}