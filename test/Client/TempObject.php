<?php

namespace MoceanTest\Client;


use Mocean\Client;
use Mocean\Client\Factory\MapFactory;
use PHPUnit\Framework\TestCase;

class TempObject implements Client\ClientAwareInterface
{
    private $client;

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
