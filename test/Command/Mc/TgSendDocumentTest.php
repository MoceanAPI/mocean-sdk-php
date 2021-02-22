<?php

namespace MoceanTest\Command\Mc;

use Mocean\Command\Mc\TgSendDocument;
use MoceanTest\AbstractTesting;

class TgSendDocumentTest extends AbstractTesting
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
                "type" => "document",
                "rich_media_url" => "https://moceanapi.com",
                "text" =>  "hello world"
            ),
            "action" => "send-telegram",
        );

        $TgSendText = new TgSendDocument();
        $TgSendText->setFrom("testbot");
        $TgSendText->setTo("123456789");
        $TgSendText->setContent("https://moceanapi.com","hello world");
        $this->assertEquals($params, $TgSendText->getRequestData());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `event-url` from Mocean\Voice\Mc\Collect
     */
    public function testRequiredField()
    {
        $req = new TgSendDocument();
        $req->getRequestData();
    }
}