<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 5:37 PM.
 */

namespace MoceanTest\Voice\Mc;

use MoceanTest\AbstractTesting;

class CollectTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = [
            'event-url'   => 'testing event url',
            'min'         => 1,
            'max'         => 10,
            'terminators' => '#',
            'timeout'     => 10000,
            'action'      => 'collect',
        ];
        $req = new \Mocean\Voice\Mc\Collect($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Voice\Mc\Collect();
        $setterReq->setEventUrl('testing event url');
        $setterReq->setMin(1);
        $setterReq->setMax(10);
        $setterReq->setTerminators('#');
        $setterReq->setTimeout(10000);

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testIfActionAutoDefined()
    {
        $params = [
            'event-url'   => 'testing event url',
            'min'         => 1,
            'max'         => 10,
            'terminators' => '#',
            'timeout'     => 10000,
        ];
        $req = new \Mocean\Voice\Mc\Collect($params);

        $this->assertEquals('collect', $req->getRequestData()['action']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `event-url` from Mocean\Voice\Mc\Collect
     */
    public function testIfRequiredFieldNotSet()
    {
        $req = new \Mocean\Voice\Mc\Collect();
        $req->getRequestData();
    }
}
