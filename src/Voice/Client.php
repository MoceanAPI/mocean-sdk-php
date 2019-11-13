<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:14 PM.
 */

namespace Mocean\Voice;

use GuzzleHttp\Psr7\Request;
use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public function call($voice)
    {
        if (!($voice instanceof ModelInterface)) {
            if (!\is_array($voice)) {
                throw new \RuntimeException('voice must implement `'.ModelInterface::class.'` or be an array`');
            }

            foreach (['mocean-to'] as $param) {
                if (!isset($voice[$param])) {
                    throw new \InvalidArgumentException('missing expected key `'.$param.'`');
                }
            }

            $to = $voice['mocean-to'];
            $mc = null;
            if (isset($voice['mocean-command'])) {
                $mc = $voice['mocean-command'];
            }

            unset($voice['mocean-to'], $voice['mocean-command']);
            $voice = new Voice($to, $mc, $voice);
        }

        $params = $voice->getRequestData();

        $request = new Request(
            'POST',
            '/voice/dial',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
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
            'POST',
            '/voice/hangup',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query(['mocean-call-uuid' => $callUuid]));
        $response = $this->client->send($request);

        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return Voice::createFromResponse($data, $this->client->version);
    }

    public function recording($callUuid)
    {
        $request = new Request(
            'GET',
            '/voice/rec?'.http_build_query(['mocean-call-uuid' => $callUuid])
        );

        $response = $this->client->send($request);

        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        //return as buffer
        if (in_array('audio/mpeg', $response->getHeader('Content-Type'))) {
            return new Recording($data, $callUuid.'.mp3');
        }

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return Recording::createFromResponse($data, $this->client->version);
    }
}
