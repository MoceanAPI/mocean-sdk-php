<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 5:37 PM.
 */

namespace MoceanTest\Voice\Mc;

use MoceanTest\AbstractTesting;

class SayTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = [
            'language'          => 'testing language',
            'text'              => 'testing text',
            'barge-in'          => true,
            'clear-digit-cache' => true,
            'action'            => 'say',
        ];
        $req = new \Mocean\Voice\Mc\Say($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Voice\Mc\Say();
        $setterReq->setLanguage('testing language');
        $setterReq->setText('testing text');
        $setterReq->setBargeIn(true);
        $setterReq->setClearDigitCache(true);

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testIfActionAutoDefined()
    {
        $params = [
            'language' => 'testing language',
            'text'     => 'testing text',
            'barge-in' => true,
        ];
        $req = new \Mocean\Voice\Mc\Say($params);

        $this->assertEquals('say', $req->getRequestData()['action']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `text` from Mocean\Voice\Mc\Say
     */
    public function testIfRequiredFieldNotSet()
    {
        $req = new \Mocean\Voice\Mc\Say();
        $req->getRequestData();
    }
}
