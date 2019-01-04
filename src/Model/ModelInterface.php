<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 12/17/2018
 * Time: 10:31 AM.
 */

namespace Mocean\Model;

interface ModelInterface extends \ArrayAccess
{
    public function getRequestData();

    public function getRawResponseData();

    public static function createFromResponse($responseData);

    public function __get($name);

    public function __toString();
}
