<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Factory;

/**
 * Interface FactoryInterface.
 *
 * Factor create API clients (clients specific to single API, that leverages Mocean\Client for HTTP communication and
 * common functionality).
 */
interface FactoryInterface
{
    /**
     * @param $api
     *
     * @return bool
     */
    public function hasApi($api);

    /**
     * @param $api
     *
     * @return mixed
     */
    public function getApi($api);
}
