<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018  Micro Ocean, Inc.
 * @license  MIT License
 */

namespace Mocean\Client\Credentials;

abstract class AbstractCredentials implements CredentialsInterface
{
    protected $credentials = [];

    public function offsetExists($offset)
    {
        return isset($this->credentials[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->credentials[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw $this->readOnlyException();
    }

    public function offsetUnset($offset)
    {
        throw $this->readOnlyException();
    }

    public function __get($name)
    {
        return $this->credentials[$name];
    }

    public function asArray()
    {
        return $this->credentials;
    }

    protected function readOnlyException()
    {
        return new \RuntimeException(sprintf(
                '%s is read only, cannot modify using array access.',
                get_class($this)
            ));
    }
}
