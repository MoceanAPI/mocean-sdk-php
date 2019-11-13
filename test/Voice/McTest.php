<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/12/2019
 * Time: 9:41 AM.
 */

namespace MoceanTest\Voice;

use Mocean\Voice\Mc;
use MoceanTest\AbstractTesting;

class McTest extends AbstractTesting
{
    public function testMcSay()
    {
        $say = Mc::say();
        $this->assertInstanceOf(Mc\Say::class, $say);

        $say = Mc::say('hello world');
        $this->assertEquals($say->getRequestData()['text'], 'hello world');
    }

    public function testMcDial()
    {
        $dial = Mc::dial();
        $this->assertInstanceOf(Mc\Dial::class, $dial);

        $dial = Mc::dial('testing to');
        $this->assertEquals($dial->getRequestData()['to'], 'testing to');
    }

    public function testMcCollect()
    {
        $collect = Mc::collect();
        $this->assertInstanceOf(Mc\Collect::class, $collect);

        $collect = Mc::collect('testing url');
        $collect->setMin(1)
            ->setMax(10)
            ->setTimeout(500);
        $this->assertEquals($collect->getRequestData()['event-url'], 'testing url');
    }

    public function testMcPlay()
    {
        $play = Mc::play();
        $this->assertInstanceOf(Mc\Play::class, $play);

        $play = Mc::play('testing file');
        $this->assertEquals($play->getRequestData()['file'][0], 'testing file');

        $play = Mc::play(['testing file 1', 'testing file 2']);
        $this->assertEquals($play->getRequestData()['file'], ['testing file 1', 'testing file 2']);
    }

    public function testMcSleep()
    {
        $sleep = Mc::sleep();
        $this->assertInstanceOf(Mc\Sleep::class, $sleep);

        $sleep = Mc::sleep(10000);
        $this->assertEquals($sleep->getRequestData()['duration'], 10000);
    }

    public function testMcRecord()
    {
        $record = Mc::record();
        $this->assertInstanceOf(Mc\Record::class, $record);
    }
}
