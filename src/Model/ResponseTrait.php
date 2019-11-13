<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 12:31 PM.
 */

namespace Mocean\Model;

trait ResponseTrait
{
    protected $rawResponseData;
    protected $responseData;

    protected function processResponse($version)
    {
        //test if object is json
        $obj = json_decode($this->rawResponseData);
        if ($obj === null) {
            //assume json decode failed, it might be in xml format
            if ($version === '1') {
                $responseData = $this->formatV1ResponseBeforeProcess();
            } else {
                $responseData = $this->replaceVerifyWrapper($this->rawResponseData);
            }
            $obj = simplexml_load_string($responseData);
        }

        if ($obj === false) {
            //if xml decode also failed, means there's something wrong
            throw new \RuntimeException('failed to format response');
        }

        $this->responseData = $this->formatResponseObjAfterProcess(
            json_decode(json_encode($obj), true)
        );
    }

    protected function setRawResponseData($rawResponseData)
    {
        $this->rawResponseData = $rawResponseData;

        return $this;
    }

    protected function formatV1ResponseBeforeProcess()
    {
        $responseData = $this->replaceVerifyWrapper($this->rawResponseData);

        if (self::class === 'Mocean\Account\Price') {
            $responseData = str_replace(
                ['<data>', '</data>'],
                ['<destinations>', '</destinations>'],
                $responseData
            );
        } elseif (self::class === 'Mocean\Message\Message') {
            $responseData = str_replace(
                ['<result>', '</result>'],
                ['<result><messages>', '</messages></result>'],
                $responseData
            );
        }

        return $responseData;
    }

    protected function formatResponseObjAfterProcess($obj)
    {
        if (self::class === 'Mocean\Account\Price' && isset($obj['destinations']['destination'])) {
            $obj['destinations'] = $obj['destinations']['destination'];
        } elseif (self::class === 'Mocean\Message\Message' && isset($obj['messages']['message'])) {
            if (!is_array(json_decode(json_encode($obj['messages']['message'])))) {
                $obj['messages']['message'] = [$obj['messages']['message']];
            }
            $obj['messages'] = $obj['messages']['message'];
        } elseif (self::class === 'Mocean\Voice\Voice' && isset($obj['calls']['call'])) {
            if (!is_array(json_decode(json_encode($obj['calls']['call'])))) {
                $obj['calls']['call'] = [$obj['calls']['call']];
            }
            $obj['calls'] = $obj['calls']['call'];
        }

        return $obj;
    }

    protected function replaceVerifyWrapper($responseData)
    {
        return str_replace(['<verify_request>', '</verify_request>', '<verify_check>', '</verify_check>'], '', $responseData);
    }

    public function getRawResponseData()
    {
        return $this->rawResponseData;
    }
}
