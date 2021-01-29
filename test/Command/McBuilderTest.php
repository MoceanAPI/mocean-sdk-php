<?php

namespace MoceanTest\Command;

use Mocean\Command\CommandMc;
use Mocean\Command\McBuilder;
use MoceanTest\AbstractTesting;

class McBuilderTest extends AbstractTesting
{
    public function testMcBuilderConstructor()
    {
        $this->assertInstanceOf(McBuilder::class, new McBuilder());
        $this->assertInstanceOf(McBuilder::class, McBuilder::create());
    }

    public function testAdd()
    {
        $tgSendText = CommandMc::tgSendText()
                            ->setFrom("botusername")
                            ->setTo("123456789")
                            ->setContent("Hello world");

        $req = McBuilder::create();
        $req->add($tgSendText);
    }

    public function testBuild()
    {
        $tgSendText = CommandMc::tgSendText()
            ->setFrom("botusername")
            ->setTo("123456789")
            ->setContent("Hello world");

        $req = McBuilder::create();
        $req->add($tgSendText);

        $this->assertCount(1,$req->build());
        $this->assertEquals($tgSendText->getRequestData(),$req->build()[0]);
    }
}