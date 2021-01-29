<?php

namespace MoceanTest\Command;

use Mocean\Command\CommandMc;
use Mocean\Command\Mc\TgRequestContact;
use Mocean\Command\Mc\TgSendAnimation;
use Mocean\Command\Mc\TgSendAudio;
use Mocean\Command\Mc\TgSendDocument;
use Mocean\Command\Mc\TgSendText;
use Mocean\Command\Mc\TgSendVideo;
use MoceanTest\AbstractTesting;

class McTest extends AbstractTesting
{
    public function testTgSendText()
    {
        $this->assertInstanceOf(TgSendText::class, Mc::tgSendText());
    }

    public function testTgSendAudio()
    {
        $this->assertInstanceOf(TgSendAudio::class, Mc::tgSendAudio());
    }

    public function testTgSendAnimation()
    {
        $this->assertInstanceOf(TgSendAnimation::class, Mc::tgSendAnimation());
    }

    public function testTgSendDocument()
    {
        $this->assertInstanceOf(TgSendDocument::class, Mc::tgSendDocument());
    }

    public function testTgSendVideo()
    {
        $this->assertInstanceOf(TgSendVideo::class, Mc::tgSendDocument());
    }

    public function testTgRequestContact()
    {
        $this->assertInstanceOf(TgRequestContact::class, Mc::tgRequestContact());
    }
}