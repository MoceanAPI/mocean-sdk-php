<?php

namespace MoceanTest\Command\Mc;

use Mocean\Command\Mc\TgSendAudio;
use MoceanTest\AbstractTesting;

class TgSendAudioTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = array(
            "from" => array(
                "type" => "bot_username",
                "id" => "testbot"
            ),
            "to" => array(
                "type" => "chat_id",
                "id" => "123456789"
            ),
            "content" => array(
                "type" => "audio",
                "rich_media_url" => "https://moceanapi.com",
                "text" =>  "hello world"
            ),
            "action" => "send-telegram",
        );

        $req = new TgSendAudio();
        $req->setFrom("testbot");
        $req->setTo("123456789");
        $req->setContent("https://moceanapi.com","hello world");
        $this->assertEquals($params, $req->getRequestData());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `to` from Mocean\Command\Mc\TgSendAudio
     */
    public function testRequiredField()
    {
        $req = new TgSendAudio();
        $req->getRequestData();
    }
}