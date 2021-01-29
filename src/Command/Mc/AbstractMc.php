<?php

namespace Mocean\Command\Mc;

use Mocean\Model\AsRequest;
use Mocean\Client\Exception\Exception;

abstract class AbstractMc implements AsRequest
{
    protected $requestData;

    abstract public function action();

    abstract protected function requiredKey();

    public function getRequestData()
    {
        foreach ($this->requiredKey() as $param) {
            if (!isset($this->requestData[$param])) {
                throw new \InvalidArgumentException('missing expected key `'.$param.'` from '.static::class);
            }
        }

        return array_merge($this->requestData, [
            'action' => $this->action(),
        ]);
    }
}