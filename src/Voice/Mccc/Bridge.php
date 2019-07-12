<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 9:10 AM.
 */

namespace Mocean\Voice\Mccc;


class Bridge extends AbstractMccc
{
    public function __construct($params = null)
    {
        parent::__construct($params);
    }

    public function setTo($to)
    {
        $this->requestData['to'] = $to;
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
