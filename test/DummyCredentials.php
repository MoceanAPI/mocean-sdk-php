<?php
/**
 * @author Lam Kai Loon <lkloon123@hotmail.com>
 */

namespace MoceanTest;

use Mocean\Client\Credentials\CredentialsInterface;

class DummyCredentials implements CredentialsInterface
{
    public function offsetExists($offset)
    {
    }

    public function offsetGet($offset)
    {
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }

    public function asArray()
    {
    }
}
