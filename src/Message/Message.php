<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 4:38 PM.
 */

namespace Mocean\Message;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class Message implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $requestData = [];

    /**
     * Message constructor.
     *
     * @param $from
     * @param $to
     * @param $text
     * @param array $extra
     */
    public function __construct($from, $to, $text, $extra = [])
    {
        $this->requestData['mocean-from'] = $from;
        $this->requestData['mocean-to'] = $to;
        $this->requestData['mocean-text'] = $text;
        $this->requestData = array_merge($this->requestData, $extra);
    }

    /**
     * @param $responseData
     *
     * @throws Exception
     *
     * @return Message
     */
    public static function createFromResponse($responseData, $version)
    {
        $message = new self(null, null, null);
        $message->setRawResponseData($responseData)
            ->processResponse($version);

        if (isset($message['status']) && $message['status'] !== 0 && $message['status'] !== '0') {
            throw new Exception($message['err_msg']);
        }

        return $message;
    }

    public function setFrom($from)
    {
        $this->requestData['mocean-from'] = $from;

        return $this;
    }

    public function setTo($to)
    {
        $this->requestData['mocean-to'] = $to;

        return $this;
    }

    public function setText($text)
    {
        $this->requestData['mocean-text'] = $text;

        return $this;
    }

    public function setUdh($udh)
    {
        $this->requestData['mocean-udh'] = $udh;

        return $this;
    }

    public function setCoding($coding)
    {
        $this->requestData['mocean-coding'] = $coding;

        return $this;
    }

    public function setDlrMask($dlrMask)
    {
        $this->requestData['mocean-dlr-mask'] = $dlrMask;

        return $this;
    }

    public function setDlrUrl($dlrUrl)
    {
        $this->requestData['mocean-dlr-url'] = $dlrUrl;

        return $this;
    }

    public function setSchedule($schedule)
    {
        $this->requestData['mocean-schedule'] = $schedule;

        return $this;
    }

    public function setMclass($mclass)
    {
        $this->requestData['mocean-mclass'] = $mclass;

        return $this;
    }

    public function setAltDcs($altDcs)
    {
        $this->requestData['mocean-alt-dcs'] = $altDcs;

        return $this;
    }

    public function setCharset($charset)
    {
        $this->requestData['mocean-charset'] = $charset;

        return $this;
    }

    public function setValidity($validity)
    {
        $this->requestData['mocean-validity'] = $validity;

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
