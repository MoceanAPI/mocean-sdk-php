<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 4:29 PM.
 */

namespace Mocean\Message;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsRequest;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class MessageStatus implements ModelInterface, AsRequest, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected static $messageStatusType = [
        1 => 'Transaction success',
        2 => 'Transaction failed',
        3 => 'Transaction failed due to message expired',
        4 => 'Transaction pending for final status',
        5 => 'Transaction not found',
    ];

    protected $requestData = [];

    /**
     * Balance constructor.
     *
     * @param array $extra
     */
    public function __construct($msgId, $extra = [])
    {
        $this->requestData['mocean-msgid'] = $msgId;
        $this->requestData = array_merge($this->requestData, $extra);
    }

    /**
     * @param $responseData
     *
     * @throws Exception
     *
     * @return MessageStatus
     */
    public static function createFromResponse($responseData, $version)
    {
        $messageStatus = new self(null);
        $messageStatus->setRawResponseData($responseData)
            ->processResponse($version);

        if ($messageStatus['status'] !== 0 && $messageStatus['status'] !== '0') {
            throw new Exception($messageStatus['err_msg']);
        }

        //show message status instead of integer status code
        $messageStatus['message_status'] = self::$messageStatusType[$messageStatus['message_status']];

        return $messageStatus;
    }

    public function setResponseFormat($responseFormat)
    {
        $this->requestData['mocean-resp-format'] = $responseFormat;

        return $this;
    }

    public function setMsgId($msgId)
    {
        $this->requestData['mocean-msgid'] = $msgId;

        return $this;
    }

    public function getRequestData()
    {
        return $this->requestData;
    }
}
