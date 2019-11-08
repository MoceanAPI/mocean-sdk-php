<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 4/8/2019
 * Time: 9:41 AM.
 */

namespace Mocean\NumberLookup;

use GuzzleHttp\Psr7\Request;
use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception;
use Mocean\Model\ModelInterface;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    public function inquiry($numberLookup)
    {
        if (!($numberLookup instanceof ModelInterface)) {
            if (!\is_array($numberLookup)) {
                throw new \RuntimeException('number lookup must implement `'.ModelInterface::class.'` or be an array`');
            }

            foreach (['mocean-to'] as $param) {
                if (!isset($numberLookup[$param])) {
                    throw new \InvalidArgumentException('missing expected key `'.$param.'`');
                }
            }

            $to = $numberLookup['mocean-to'];
            unset($numberLookup['mocean-to']);
            $numberLookup = new NumberLookup($to, $numberLookup);
        }

        $params = $numberLookup->getRequestData();

        $request = new Request(
            'POST',
            '/nl',
            ['content-type' => 'application/x-www-form-urlencoded']
        );

        $request->getBody()->write(http_build_query($params));
        $response = $this->client->send($request);
        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        if (!isset($data) || $data === '') {
            throw new Exception\Exception('unexpected response from API');
        }

        return NumberLookup::createFromResponse($data, $this->client->version);
    }
}
