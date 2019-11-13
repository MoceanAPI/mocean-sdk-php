<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:59 PM.
 */

namespace Mocean\Voice;

use Mocean\Voice\Mc\AbstractMc;

class McBuilder
{
    protected $mc = [];

    /**
     * Sugar syntax for McBuilder constructor.
     *
     * @return McBuilder
     */
    public static function create()
    {
        return new self();
    }

    public function add(AbstractMc $mc)
    {
        $this->mc[] = $mc;

        return $this;
    }

    public function build()
    {
        $converted = [];
        foreach ($this->mc as $m) {
            /* @var AbstractMc $m */
            $converted[] = $m->getRequestData();
        }

        return $converted;
    }
}
