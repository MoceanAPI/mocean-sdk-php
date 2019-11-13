<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 10/30/2019
 * Time: 4:15 PM.
 */

namespace Mocean\Voice\Mc;

class Record extends AbstractMc
{
    protected function requiredKey()
    {
        return [];
    }

    protected function action()
    {
        return 'record';
    }
}
