<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 2:51 PM.
 */

namespace MoceanTest;

use PHPUnit\Framework\TestCase;

class AbstractTesting extends TestCase
{
    protected $apiKey = 'test_api_key';
    protected $apiSecret = 'test_api_secret';

    public function getClass($class, $property, $object)
    {
        $reflectionClass = new \ReflectionClass($class);
        $refProperty = $reflectionClass->getProperty($property);
        $refProperty->setAccessible(true);

        return $refProperty->getValue($object);
    }
}
