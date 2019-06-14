<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/3/2019
 * Time: 2:51 PM.
 */

namespace MoceanTest;

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Response;

class AbstractTesting extends TestCase
{
    protected $apiKey = 'test_api_key';
    protected $apiSecret = 'test_api_secret';
    protected $defaultVersion = '2';

    public function getClass($class, $property, $object)
    {
        $reflectionClass = new \ReflectionClass($class);
        $refProperty = $reflectionClass->getProperty($property);
        $refProperty->setAccessible(true);

        return $refProperty->getValue($object);
    }

    protected function getResponse($fileName, $status = 200)
    {
        return new Response(fopen(__DIR__.'/Resources/'.$fileName, 'r'), $status);
    }

    protected function getResponseString($fileName)
    {
        return file_get_contents(__DIR__.'/Resources/'.$fileName);
    }

    protected function convertArrayFromQueryString($queryStr)
    {
        parse_str($queryStr, $output);

        return $output;
    }
}
