<?php

namespace MoceanTest\Command\Mc;

use Mocean\Command\Mc\SendSMS;
use MoceanTest\AbstractTesting;

class SendSMSTest extends AbstractTesting
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
            "action" => "send-sms",
        );

        $req = new SendSMS();
        $req->setFrom("testbot");
        $req->setTo("123456789");
        $req->setContent("hello world");
        $this->assertEquals($params, $req->getRequestData());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage missing expected key `event-url` from Mocean\Voice\Mc\Collect
     */
    public function testRequiredField()
    {
        $req = new SendSMS();
        $req->getRequestData();
    }
}