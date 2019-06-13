<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 3:39 PM.
 */

namespace Mocean\Model;

trait ObjectAccessTrait
{
    public function __get($name)
    {
        $tmp = json_decode(json_encode($this->responseData));

        return isset($tmp->$name) ? $tmp->$name : null;
    }

    public function __toString()
    {
        if ($this->getRawResponseData() !== null) {
            return $this->getRawResponseData();
        }

        return 'this is not a response object';
    }
}
