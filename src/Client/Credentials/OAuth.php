<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Credentials;

class OAuth extends AbstractCredentials implements CredentialsInterface
{
    /**
     * Create a credential set with OAuth credentials.
     *
     * @param string $consumerToken
     * @param string $consumerSecret
     * @param string $token
     * @param string $secret
    */
    public function __construct($consumerToken, $consumerSecret, $token, $secret)
    {
        //using keys that match guzzle 
        $this->credentials = array_combine(array('consumer_key', 'consumer_secret', 'token', 'token_secret'), func_get_args());
    }
}