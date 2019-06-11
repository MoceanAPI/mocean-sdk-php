<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 6/11/2019
 * Time: 10:25 AM.
 */

namespace MoceanTest\Model;


use Mocean\Model\ArrayAccessTrait;

class ObjectUsedByTrait implements \ArrayAccess
{
    use ArrayAccessTrait;

    public $responseData;

    /**
     * @return mixed
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * @param mixed $responseData
     */
    public function setResponseData($responseData)
    {
        $this->responseData = $responseData;
    }
}
