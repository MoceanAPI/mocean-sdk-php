<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Response;

interface ResponseInterface
{
    public function getData();

    public function isError();

    public function isSuccess();
}
