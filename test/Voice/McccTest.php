<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/12/2019
 * Time: 9:41 AM.
 */

namespace MoceanTest\Voice;


use Mocean\Voice\Mccc;
use MoceanTest\AbstractTesting;

class McccTest extends AbstractTesting
{
    public function testMcccSay()
    {
        $say = Mccc::say();
        $this->assertInstanceOf(Mccc\Say::class, $say);

        $say = Mccc::say('hello world');
        $this->assertEquals($say->getRequestData()['text'], 'hello world');
    }

    public function testMcccBridge()
    {
        $bridge = Mccc::bridge();
        $this->assertInstanceOf(Mccc\Bridge::class, $bridge);

        $bridge = Mccc::bridge('testing to');
        $this->assertEquals($bridge->getRequestData()['to'], 'testing to');
    }

    public function testMcccCollect()
    {
        $collect = Mccc::collect();
        $this->assertInstanceOf(Mccc\Collect::class, $collect);

        $collect = Mccc::collect('testing url');
        $this->assertEquals($collect->getRequestData()['event-url'], 'testing url');
    }

    public function testMcccPlay()
    {
        $play = Mccc::play();
        $this->assertInstanceOf(Mccc\Play::class, $play);

        $play = Mccc::play('testing file');
        $this->assertEquals($play->getRequestData()['file'][0], 'testing file');

        $play = Mccc::play(['testing file 1', 'testing file 2']);
        $this->assertEquals($play->getRequestData()['file'], ['testing file 1', 'testing file 2']);
    }

    public function testMcccSleep()
    {
        $sleep = Mccc::sleep();
        $this->assertInstanceOf(Mccc\Sleep::class, $sleep);

        $sleep = Mccc::sleep(10000);
        $this->assertEquals($sleep->getRequestData()['duration'], 10000);
    }
}
