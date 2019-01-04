<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Credentials;

class SharedSecret extends AbstractCredentials implements CredentialsInterface
{
    /**
     * Create a credential set with an API key and shared secret.
     *
     * @param string $key    API Key
     * @param string $secret Shared Secret
     */
    public function __construct($key, $secret)
    {
        $this->credentials['api_key'] = $key;
        $this->credentials['shared_secret'] = $secret;
    }
}
