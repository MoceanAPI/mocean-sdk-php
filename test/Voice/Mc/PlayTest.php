<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 5:37 PM.
 */

namespace MoceanTest\Voice\Mc;

use MoceanTest\AbstractTesting;

class PlayTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = [
            'file'              => 'testing file',
            'barge-in'          => true,
            'clear-digit-cache' => true,
            'action'            => 'play',
        ];
        $req = new \Mocean\Voice\Mc\Play($params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Voice\Mc\Play();
        $setterReq->addFile('first file');
        $setterReq->addFile('second file');
        $this->assertEquals(['first file', 'second file'], $setterReq->getRequestData()['file']);

        $setterReq->setFiles(null);

        try {
            $setterReq->getRequestData();
            self::fail('object can be use without required field filled');
        } catch (\Exception $e) {
        }
        $setterReq->addFile('first file');
        $setterReq->addFile(['second file', 'third file']);
        $this->assertEquals(['first file', 'second file', 'third file'], $setterReq->getRequestData()['file']);

        $setterReq->setFiles(null);

        try {
            $setterReq->getRequestData();
            self::fail('object can be use without required field filled');
        } catch (\Exception $e) {
        }
        $setterReq->addFile(['second file', 'third file']);
        $this->assertEquals(['second file', 'third file'], $setterReq->getRequestData()['file']);

        $setterReq->setFiles('testing file');
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
