<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc. 
 * @license MIT License
 */

namespace Mocean\Client;

use Mocean\Client;

trait ClientAwareTrait
{
    /**
     * @var Client
     */
    protected $client;

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    protected function getClient()
    {
        if(isset($this->client)){
            return $this->client;
        }

        throw new \RuntimeException('Mocean\Client not set');
    }
}