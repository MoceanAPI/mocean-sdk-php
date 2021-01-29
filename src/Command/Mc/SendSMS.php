<?php

namespace Mocean\Command\Mc;

class SendSMS extends AbstractMc
{
    public function setTo($id, $type = "phone_num")
    {
        $this->requestData['to'] = array(
            "id" => $id,
            "type" => $type
        );

        return $this;
    }

    public function setFrom($id, $type = "phone_num")
    {
        $this->requestData['from'] = array(
            "id" => $id,
            "type" => $type,
        );

        return $this;
    }

    public function setContent($text = "") {
        $this->requestData["content"] = array(
            "type" => "type",
            "text" => $text,
        );
        return $this;
    }

    protected function requiredKey()
    {
        return ['to','from','content'];
    }

    public function action()
    {
        return 'send-sms';
    }
}
