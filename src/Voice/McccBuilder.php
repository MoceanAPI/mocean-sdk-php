<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:59 PM.
 */

namespace Mocean\Voice;


use Mocean\Voice\Mccc\AbstractMccc;

class McccBuilder
{
    protected $mccc = [];

    /**
     * Sugar syntax for McccBuilder constructor
     *
     * @return McccBuilder
     */
    public static function create()
    {
        return new self();
    }

    public function add(AbstractMccc $mccc)
    {
        $this->mccc[] = $mccc;
        return $this;
    }

    public function build()
    {
        $converted = [];
        foreach ($this->mccc as $m) {
            /* @var AbstractMccc $m */
            $converted[] = $m->getRequestData();
        }
        return $converted;
    }
}
