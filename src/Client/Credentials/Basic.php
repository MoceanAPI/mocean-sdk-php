<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Credentials;

/**
 * Class Basic
 * Read-only container for api key and secret.
 */
class Basic extends AbstractCredentials
{
    /**
     * Create a credential set with an API key and secret.
     *
     * @param string $key
     * @param string $secret
     */
    public function __construct($key, $secret)
    {
        $this->credentials['mocean-api-key'] = $key;
        $this->credentials['mocean-api-secret'] = $secret;
    }
}
