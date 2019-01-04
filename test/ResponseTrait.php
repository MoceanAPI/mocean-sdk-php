<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 1/4/2019
 * Time: 10:03 AM
 */

namespace MoceanTest;


use Zend\Diactoros\Response;

trait ResponseTrait
{
    protected function getResponse($path, $status = 200)
    {
        return new Response(fopen($path, 'r'), $status);
    }

    protected function getResponseString($path)
    {
        return file_get_contents($path);
    }

    protected function convertArrayFromQueryString($queryStr)
    {
        parse_str($queryStr, $output);

        return $output;
    }
}
