<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:54 PM.
 */

namespace Mocean\Voice\Mc;

class Sleep extends AbstractMc
{
    public function setDuration($duration)
    {
        $this->requestData['duration'] = $duration;

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
