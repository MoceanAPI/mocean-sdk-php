<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Request;

interface RequestInterface
{
    /**
     * @return array
     */
    public function getParams();

    /**
     * @return string
     */
    public function getURI();
}
