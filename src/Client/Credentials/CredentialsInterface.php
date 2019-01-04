<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Credentials;

interface CredentialsInterface extends \ArrayAccess
{
    public function asArray();
}
