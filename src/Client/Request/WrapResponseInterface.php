<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Request;

use Mocean\Client\Response\ResponseInterface;

interface WrapResponseInterface
{
    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function wrapResponse(ResponseInterface $response);
}