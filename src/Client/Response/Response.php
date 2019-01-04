<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean\Client\Response;

class Response extends AbstractResponse implements ResponseInterface
{
    /**
     * Allow specific responses to easily define required parameters.
     *
     * @var array
     */
    protected $expected = [];

    public function __construct(array $data)
    {
        $keys = array_keys($data);
        $missing = array_diff($this->expected, $keys);

        if ($missing) {
            throw new \RuntimeException('missing expected response keys: '.implode(', ', $missing));
        }

        $this->data = $data;
    }
}
