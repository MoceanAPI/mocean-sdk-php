<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 11:59 AM.
 */

namespace Mocean\Voice\Mc;

class Collect extends AbstractMc
{
    public function setEventUrl($eventUrl)
    {
        $this->requestData['event-url'] = $eventUrl;

        return $this;
    }

    public function setMin($min)
    {
        $this->requestData['min'] = $min;

        return $this;
    }

    public function setMax($max)
    {
        $this->requestData['max'] = $max;

        return $this;
    }

    public function setTerminators($terminators)
    {
        $this->requestData['terminators'] = $terminators;

        return $this;
    }

    public function setTimeout($timeout)
    {
        $this->requestData['timeout'] = $timeout;

        return $this;
    }

    protected function requiredKey()
    {
        return ['event-url', 'min', 'max', 'timeout'];
    }

    protected function action()
    {
        return 'collect';
    }
}
