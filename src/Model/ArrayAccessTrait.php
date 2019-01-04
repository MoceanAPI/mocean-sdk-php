<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 2:36 PM.
 */

namespace Mocean\Model;

trait ArrayAccessTrait
{
    public function offsetExists($offset)
    {
        return isset($this->responseData[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->responseData[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->responseData[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->responseData[$offset]);
    }
}
