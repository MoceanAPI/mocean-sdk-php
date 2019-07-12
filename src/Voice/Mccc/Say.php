<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:16 PM.
 */

namespace Mocean\Voice\Mccc;

class Say extends AbstractMccc
{
    public function __construct($params = null)
    {
        parent::__construct($params);

        if (!isset($this->requestData['language'])) {
            $this->requestData['language'] = 'en-US';
        }
    }

    public function setLanguage($language)
    {
        $this->requestData['language'] = $language;
        return $this;
    }

    public function setText($text)
    {
        $this->requestData['text'] = $text;
        return $this;
    }

    public function setBargeIn($bargeIn)
    {
        $this->requestData['barge-in'] = $bargeIn;
        return $this;
    }

    protected function requiredKey()
    {
        return ['text', 'language'];
    }

    protected function action()
    {
        return 'say';
    }
}
