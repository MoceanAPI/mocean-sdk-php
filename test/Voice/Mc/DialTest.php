<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 5:37 PM.
 */

namespace MoceanTest\Voice\Mc;

use MoceanTest\AbstractTesting;

class DialTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = [
            'to'                => 'testing to',
            'action'            => 'dial',
            'from'              => 'callerid',
            'dial-sequentially' => true,
        ];
        $req = new \Mocean\Voice\Mc\Dial($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Voice\Mc\Dial();
        $setterReq->setTo('testing to');
        $setterReq->setFrom('callerid');
        $setterReq->setDialSequentially(true);

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testIfActionAutoDefined()
    {
        $params = [
            'to' => 'testing to',
        ];
        $req = new \Mocean\Voice\Mc\Dial($params);

        $this->assertEquals('dial', $req->getRequestData()['action']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `to` from Mocean\Voice\Mc\Dial
     */
    public function testIfRequiredFieldNotSet()
    {
        $req = new \Mocean\Voice\Mc\Dial();
        $req->getRequestData();
    }
}
