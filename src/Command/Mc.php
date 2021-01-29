<?php

namespace Mocean\Command;

use Mocean\Command\Mc\ContactType;
use Mocean\Command\Mc\SendSMS;
use Mocean\Command\Mc\TgSendPhoto;
use Mocean\Command\Mc\TgSendText;
use Mocean\Command\Mc\TgSendAudio;
use Mocean\Command\Mc\TgSendAnimation;
use Mocean\Command\Mc\TgSendDocument;
use Mocean\Command\Mc\TgSendVideo;
use Mocean\Command\Mc\TgRequestContact;


class Mc
{
    public static function tgSendText()
    {
        return new TgSendText();
    }

    public static function tgSendAudio()
    {
        return new TgSendAudio();
    }

    public static function tgSendAnimation()
    {
        return new TgSendAnimation();
    }

    public static function tgSendDocument()
    {
        return new TgSendDocument();
    }

    public static function tgSendVideo()
    {
        return new TgSendVideo();
    }

    public static function tgSendPhoto()
    {
        return new TgSendPhoto();
    }

    public static function tgRequestContact()
    {
        return new TgRequestContact();
    }

    public static function sendSMS()
    {
        return new SendSMS();
    }
}