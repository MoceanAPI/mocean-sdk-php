<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 9:15 AM.
 */

namespace Mocean\Voice;

use Mocean\Voice\Mc\Collect;
use Mocean\Voice\Mc\Dial;
use Mocean\Voice\Mc\Play;
use Mocean\Voice\Mc\Record;
use Mocean\Voice\Mc\Say;
use Mocean\Voice\Mc\Sleep;

class Mc
{
    /**
     * @param string|null $text
     *
     * @return Say
     */
    public static function say($text = null)
    {
        $ins = new Say();

        if ($text) {
            return $ins->setText($text);
        }

        return $ins;
    }

    /**
     * @param array|string|null $file
     *
     * @return Play
     */
    public static function play($file = null)
    {
        $ins = new Play();

        if (is_array($file)) {
            $ins->setFiles($file);
        } else {
            $ins->addFile($file);
        }

        return $ins;
    }

    /**
     * @param string|null $to
     *
     * @return Dial
     */
    public static function dial($to = null)
    {
        $ins = new Dial();

        if ($to) {
            $ins->setTo($to);
        }

        return $ins;
    }

    /**
     * @param string|null $eventUrl
     *
     * @return Collect
     */
    public static function collect($eventUrl = null)
    {
        $ins = new Collect();

        if ($eventUrl) {
            $ins->setEventUrl($eventUrl);
        }

        return $ins;
    }

    /**
     * @param string|null $duration duration in ms
     *
     * @return Sleep
     */
    public static function sleep($duration = null)
    {
        $ins = new Sleep();

        if ($duration) {
            return $ins->setDuration($duration);
        }

        return $ins;
    }

    /**
     * @return Record
     */
    public static function record()
    {
        return new Record();
    }
}
