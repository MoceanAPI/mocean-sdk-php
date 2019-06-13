<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client;

trait ClientAwareTrait
{
    protected $client;

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        if (isset($this->client)) {
            return $this->client;
        }

        throw new \RuntimeException('Mocean\Client not set');
    }
}
