<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 5:37 PM.
 */

namespace MoceanTest\Voice\Mccc;

use MoceanTest\AbstractTesting;

class BridgeTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = [
            'to' => 'testing to',
            'action' => 'dial'
        ];
        $req = new \Mocean\Voice\Mccc\Bridge($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Voice\Mccc\Bridge();
        $setterReq->setTo('testing to');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testIfActionAutoDefined()
    {
        $params = [
            'to' => 'testing to'
        ];
        $req = new \Mocean\Voice\Mccc\Bridge($params);

        $this->assertEquals('dial', $req->getRequestData()['action']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `to` from Mocean\Voice\Mccc\Bridge
     */
    public function testIfRequiredFieldNotSet()
    {
        $req = new \Mocean\Voice\Mccc\Bridge();
        $req->getRequestData();
    }
}
