<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client;

use Mocean\Client;

interface ClientAwareInterface
{
    public function setClient(Client $client);
}