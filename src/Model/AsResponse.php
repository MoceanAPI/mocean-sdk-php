<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:24 PM.
 */

namespace Mocean\Model;

interface AsResponse
{
    public function getRawResponseData();

    public static function createFromResponse($responseData, $version);
}
