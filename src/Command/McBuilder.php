<?php

namespace Mocean\Command;

use Mocean\Command\Mc\AbstractMc;

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