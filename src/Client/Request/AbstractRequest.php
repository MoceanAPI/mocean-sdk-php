<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Request;

abstract class AbstractRequest implements RequestInterface
{
    protected $params = array();

    /**
     * @return array
     */
    public function getParams()
    {
        return array_filter($this->params, 'is_scalar');
    }
} 