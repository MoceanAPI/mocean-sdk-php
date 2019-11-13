<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 9:10 AM.
 */

namespace Mocean\Voice\Mc;

class Dial extends AbstractMc
{
    public function setTo($to)
    {
        $this->requestData['to'] = $to;

        return $this;
    }

    public function setFrom($from)
    {
        $this->requestData['from'] = $from;

        return $this;
    }

    public function setDialSequentially($dialSequentially)
    {
        $this->requestData['dial-sequentially'] = $dialSequentially;

        return $this;
    }

    protected function requiredKey()
    {
        return ['to'];
    }

    protected function action()
    {
        return 'dial';
    }
}
