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

    protected function processResponse()
    {
        //test if object is json
        $obj = json_decode($this->rawResponseData);
        if ($obj === null) {
            //assume json decode failed, it might be in xml format
            $responseData = str_replace(['<verify_request>', '</verify_request>', '<verify_check>', '</verify_check>'], '', $this->rawResponseData);
            $obj = simplexml_load_string($responseData);
        }

        if ($obj === false) {
            //if xml decode also failed, means there's something wrong
            throw new \RuntimeException('failed to format response');
        }

        $this->responseData = json_decode(json_encode($obj), true);
    }

    protected function setRawResponseData($rawResponseData)
    {
        $this->rawResponseData = $rawResponseData;

        return $this;
    }

    public function getRawResponseData()
    {
        return $this->rawResponseData;
    }
}
