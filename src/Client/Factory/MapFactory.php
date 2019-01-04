<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Factory;

use Mocean\Client;

class MapFactory implements FactoryInterface
{
    /**
     * Map of api namespaces to classes.
     *
     * @var array
     */
    protected $map = [];

    /**
     * Map of instances.
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Mocean Client.
     *
     * @var Client
     */
    protected $client;

    public function __construct($map, Client $client)
    {
        $this->map = $map;
        $this->client = $client;
    }

    public function hasApi($api)
    {
        return isset($this->map[$api]);
    }

    public function getApi($api)
    {
        if (isset($this->cache[$api])) {
            return $this->cache[$api];
        }

        if (!$this->hasApi($api)) {
            throw new \RuntimeException(sprintf(
                'no map defined for `%s`',
                $api
            ));
        }

        $class = $this->map[$api];

        $instance = new $class();
        if ($instance instanceof Client\ClientAwareInterface) {
            $instance->setClient($this->client);
        }
        $this->cache[$api] = $instance;

        return $instance;
    }
}
