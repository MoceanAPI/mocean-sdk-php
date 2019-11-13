<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 5:37 PM.
 */

namespace MoceanTest\Voice\Mc;

use MoceanTest\AbstractTesting;

class SleepTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = [
            'duration' => 10000,
            'action'   => 'sleep',
        ];
        $req = new \Mocean\Voice\Mc\Sleep($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Voice\Mc\Sleep();
        $setterReq->setDuration(10000);

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testIfActionAutoDefined()
    {
        $params = ['duration' => 10000];
        $req = new \Mocean\Voice\Mc\Sleep($params);

        $this->assertEquals('sleep', $req->getRequestData()['action']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `duration` from Mocean\Voice\Mc\Sleep
     */
    public function testIfRequiredFieldNotSet()
    {
        $req = new \Mocean\Voice\Mc\Sleep();
        $req->getRequestData();
    }
}
