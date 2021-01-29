<?php

namespace Mocean\Command\Mc;

class TgRequestContact extends AbstractMc
{
    public function __construct () {
        $this->setButtonText("Share contact");
    }

    public function setTo($id, $type = "chat_id")
    {
        $this->requestData['to'] = array(
            "id" => $id,
            "type" => $type
        );

        return $this;
    }

    public function setFrom($id, $type = "bot_username")
    {
        $this->requestData['from'] = array(
            "id" => $id,
            "type" => $type,
        );

        return $this;
    }

    public function setContent($text = "") {
        $this->requestData["content"] = array(
            "type" => "contact",
            "text" => $text,
        );
        return $this;
    }

    public function setButtonText($text) {
        $this->requestData["tg_keyboard"] = array(
            "button_text" => $text,
            "button_request" => "contact",
        );
    }

    protected function requiredKey()
    {
        return ['to','from','content'];
    }

    public function action()
    {
        return 'send-telegram';
    }
}
