<?php
/**
 * Mocean Client Library for PHP
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Exception;

use Mocean\Entity\HasEntityTrait;

class Server extends Exception
{
    use HasEntityTrait;
}