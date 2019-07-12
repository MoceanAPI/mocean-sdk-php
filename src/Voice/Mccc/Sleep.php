<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:54 PM.
 */

namespace Mocean\Voice\Mccc;

class Sleep extends AbstractMccc
{
    public function __construct($params = null)
    {
        parent::__construct($params);
    }

    public function setDuration($duration)
    {
        $this->requestData['duration'] = $duration;
        return $this;
    }

    public function setBargeIn($bargeIn)
    {
        $this->requestData['barge-in'] = $bargeIn;
        return $this;
    }

    protected function requiredKey()
    {
        return ['duration'];
    }

    protected function action()
    {
        return 'sleep';
    }
}
