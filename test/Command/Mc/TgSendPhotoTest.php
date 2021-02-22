<?php

namespace MoceanTest\Command\Mc;

use Mocean\Command\Mc\TgSendPhoto;
use MoceanTest\AbstractTesting;

class TgSendPhotoTest extends AbstractTesting
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
                "type" => "photo",
                "rich_media_url" => "https://moceanapi.com",
                "text" =>  "hello world"
            ),
            "action" => "send-telegram",
        );

        $req = new TgSendPhoto();
        $req->setFrom("testbot");
        $req->setTo("123456789");
        $req->setContent("https://moceanapi.com","hello world");
        $this->assertEquals($params, $req->getRequestData());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `event-url` from Mocean\Voice\Mc\Collect
     */
    public function testRequiredField()
    {
        $req = new TgSendPhoto();
        $req->getRequestData();
    }
}