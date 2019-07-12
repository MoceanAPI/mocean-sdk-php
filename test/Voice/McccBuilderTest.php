<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/12/2019
 * Time: 9:30 AM.
 */

namespace MoceanTest\Voice;

use Mocean\Voice\Mccc\Play;
use Mocean\Voice\McccBuilder;
use MoceanTest\AbstractTesting;

class McccBuilderTest extends AbstractTesting
{
    public function testMcccBuilderConstructor()
    {
        $obj = new McccBuilder();
        $this->assertInstanceOf(McccBuilder::class, $obj);
        $this->assertInstanceOf(McccBuilder::class, McccBuilder::create());
    }

    public function testAdd()
    {
        $play = new Play();
        $play->setFiles('testing file');

        $builder = new McccBuilder();
        $builder->add($play);
        $this->assertCount(1, $builder->build());
        $this->assertEquals($play->getRequestData(), $builder->build()[0]);

        $play->setFiles('testing file2');
        $builder->add($play);
        $this->assertCount(2, $builder->build());
        $this->assertEquals($play->getRequestData(), $builder->build()[1]);
    }
}
