<?php

namespace MoceanTest\Command\Mc;

use Mocean\Command\Mc\TgRequestContact;
use MoceanTest\AbstractTesting;

class TgRequestContactTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = array(
            "tg_keyboard" => array(
                "button_text" => "Share contact",
                "button_request" => "contact"
            ),
            "from" => array(
                "type" => "bot_username",
                "id" => "testbot"
            ),
            "to" => array(
                "type" => "chat_id",
                "id" => "123456789"
            ),
            "content" => array(
                "type" => "text",
                "text" =>  "hello world"
            ),
            "action" => "send-telegram",
        );

        $req = new TgRequestContact();
        $req->setFrom("testbot");
        $req->setTo("123456789");
        $req->setContent("hello world");
        $req->setButtonText("Share contact");
        $this->assertEquals($params, $TgSendText->getRequestData());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `event-url` from Mocean\Voice\Mc\Collect
     */
    public function testRequiredField()
    {
        $req = new TgSendAnimation();
        $req->getRequestData();
    }
}