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
    public function __construct($params=[])
    {
        $this->credentials['mocean-api-key'] = isset($params['apiKey']) ? $params['apiKey'] : "";
        $this->credentials['mocean-api-secret'] = isset($params['apiSecret']) ? $params['apiSecret'] : "";
        $this->credentials['mocean-api-token'] = isset($params['apiToken']) ? $params['apiToken'] : "";
    }
}
