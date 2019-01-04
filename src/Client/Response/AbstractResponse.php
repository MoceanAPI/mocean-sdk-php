<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Response;

abstract class AbstractResponse implements ResponseInterface
{
    protected $data;

    public function getData()
    {
        return $this->data;
    }

    public function isSuccess()
    {
        return isset($this->data['status']) and $this->data['status'] == 0;
    }

    public function isError()
    {
        return !$this->isSuccess();
    }
}
