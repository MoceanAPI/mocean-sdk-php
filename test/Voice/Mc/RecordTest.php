<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 5:37 PM.
 */

namespace MoceanTest\Voice\Mc;

use MoceanTest\AbstractTesting;

class RecordTest extends AbstractTesting
{
    public function testIfActionAutoDefined()
    {
        $req = new \Mocean\Voice\Mc\Record();

        $this->assertEquals('record', $req->getRequestData()['action']);
    }
}
