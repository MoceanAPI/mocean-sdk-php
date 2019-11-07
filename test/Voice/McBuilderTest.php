<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/12/2019
 * Time: 9:30 AM.
 */

namespace MoceanTest\Voice;

use Mocean\Voice\Mc\Play;
use Mocean\Voice\McBuilder;
use MoceanTest\AbstractTesting;

class McBuilderTest extends AbstractTesting
{
    public function testMcBuilderConstructor()
    {
        $obj = new McBuilder();
        $this->assertInstanceOf(McBuilder::class, $obj);
        $this->assertInstanceOf(McBuilder::class, McBuilder::create());
    }

    public function testAdd()
    {
        $play = new Play();
        $play->setFiles('testing file');

        $builder = new McBuilder();
        $builder->add($play);
        $this->assertCount(1, $builder->build());
        $this->assertEquals($play->getRequestData(), $builder->build()[0]);

        $play->setFiles('testing file2');
        $builder->add($play);
        $this->assertCount(2, $builder->build());
        $this->assertEquals($play->getRequestData(), $builder->build()[1]);
    }
}
