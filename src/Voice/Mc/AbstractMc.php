<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:44 PM.
 */

namespace Mocean\Voice\Mc;

use Mocean\Model\AsRequest;

abstract class AbstractMc implements AsRequest
{
    protected $requestData;

    public function __construct($params = null)
    {
        $this->requestData = [];

        if ($params !== null) {
            $this->requestData = $params;
        }
    }

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

    abstract protected function requiredKey();

    abstract protected function action();
}
