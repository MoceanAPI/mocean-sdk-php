<?php

namespace MoceanTest\Command\Mc;

use Mocean\Command\Mc\TgSendText;
use MoceanTest\AbstractTesting;

class TgSendTextTest extends AbstractTesting
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
                "type" => "text",
                "text" =>  "hello world"
            ),
            "action" => "send-telegram",
        );

        $req = new TgSendText();
        $req->setFrom("testbot");
        $req->setTo("123456789");
        $req->setContent("hello world");
        $this->assertEquals($params, $req->getRequestData());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `to` from Mocean\Command\Mc\TgSendText
     */
    public function testRequiredField()
    {
        $req = new TgSendText();
        $req->getRequestData();
    }
}