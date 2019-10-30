<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:14 PM.
 */

namespace Mocean\Voice;

use Mocean\Client\Exception;
use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Model\ModelInterface;
use Zend\Diactoros\Request;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public function call($voice)
    {
        if (!($voice instanceof ModelInterface)) {
            if (!\is_array($voice)) {
                throw new \RuntimeException('voice must implement `' . ModelInterface::class . '` or be an array`');
            }

            foreach (['mocean-to'] as $param) {
                if (!isset($voice[$param])) {
                    throw new \InvalidArgumentException('missing expected key `' . $param . '`');
                }
            }

            $to = $voice['mocean-to'];
            $mccc = null;
            if (isset($voice['mocean-call-control-commands'])) {
                $mccc = $voice['mocean-call-control-commands'];
            }

            unset($voice['mocean-to'], $voice['mocean-call-control-commands']);
            $voice = new Voice($to, $mccc, $voice);
        }

        $params = $voice->getRequestData();

        $request = new Request(
            '/voice/dial?' . http_build_query($params),
            'POST'
        );

        $response = $this->client->send($request);

        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return Voice::createFromResponse($data, $this->client->version);
    }

    public function hangup($callUuid)
    {
        $request = new Request(
            '/voice/hangup/' . $callUuid,
            'POST'
        );

        $response = $this->client->send($request);
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return Voice::createFromResponse($data, $this->client->version);
    }
}
