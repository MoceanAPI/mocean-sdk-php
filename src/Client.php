<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Uri;
use Http\Client\HttpClient;
use Mocean\Client\Credentials\Basic;
use Mocean\Client\Credentials\CredentialsInterface;
use Mocean\Client\Factory\FactoryInterface;
use Mocean\Client\Factory\MapFactory;
use Psr\Http\Message\RequestInterface;

/**
 * Mocean API Client, allows access to the API from PHP.
 *
 * @method \Mocean\Message\Client message()
 * @method \Mocean\Account\Client account()
 * @method \Mocean\Verify\Client verify()
 * @method \Mocean\NumberLookup\Client numberLookup()
 * @method \Mocean\Voice\Client voice()
 */
class Client
{
    public $version = '2';
    public $baseUrl = 'https://rest.moceanapi.com';
    const PL = 'PHP-SDK';
    const SDK_VERSION = '2.1.0';
    /**
     * API Credentials.
     *
     * @var CredentialsInterface
     */
    protected $credentials;

    /**
     * Http Client.
     *
     * @var HttpClient
     */
    protected $client;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Create a new API client using the provided credentials.
     *
     * @param CredentialsInterface $credentials
     * @param array                $options
     * @param HttpClient|null      $client
     */
    public function __construct(CredentialsInterface $credentials, $options = [], HttpClient $client = null)
    {
        if ($client === null) {
            $client = new \Http\Adapter\Guzzle6\Client(new GuzzleClient());
        }

        $this->setHttpClient($client);

        //make sure we know how to use the credentials
        if (!($credentials instanceof Basic)) {
            throw new \RuntimeException('unknown credentials type: '.get_class($credentials));
        }

        $this->credentials = $credentials;

        $this->options = $options;

        if (isset($this->options['baseUrl'])) {
            $this->baseUrl = rtrim($this->options['baseUrl'], '/');
        }

        if (isset($this->options['version'])) {
            $this->version = $this->options['version'];
        }

        $this->setFactory(new MapFactory([
            'account'      => 'Mocean\Account\Client',
            'message'      => 'Mocean\Message\Client',
            'verify'       => 'Mocean\Verify\Client',
            'numberLookup' => 'Mocean\NumberLookup\Client',
            'voice'        => 'Mocean\Voice\Client',
        ], $this));
    }

    /**
     * Set the Http Client to used to make API requests.
     *
     * This allows the default http client to be swapped out for a HTTPlug compatible
     * replacement.
     *
     * @param HttpClient $client
     *
     * @return $this
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the Http Client used to make API requests.
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * Set the factory used to create API specific clients.
     *
     * @param FactoryInterface $factory
     *
     * @return $this
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    public static function authRequest(RequestInterface $request, CredentialsInterface $credentials)
    {
        switch ($request->getHeaderLine('content-type')) {
            case 'application/json':
                $body = $request->getBody();
                $body->rewind();
                $content = $body->getContents();
                $params = json_decode($content, true);
                $params = array_merge($params, $credentials->asArray());
                $params = array_merge($params, ['mocean-medium' => self::PL]);
                if (!isset($params['mocean-resp-format'])) {
                    $params['mocean-resp-format'] = 'json';
                }
                $body->rewind();
                $body->write(json_encode($params));
                break;
            case 'application/x-www-form-urlencoded':
                $body = $request->getBody();
                $body->rewind();
                $content = $body->getContents();
                $params = [];
                parse_str($content, $params);
                $params = array_merge($params, $credentials->asArray());
                $params = array_merge($params, ['mocean-medium' => self::PL]);
                if (!isset($params['mocean-resp-format'])) {
                    $params['mocean-resp-format'] = 'json';
                }
                $body->rewind();
                $body->write(http_build_query($params, null, '&'));
                break;
            default:
                $query = [];
                parse_str($request->getUri()->getQuery(), $query);
                $query = array_merge($query, $credentials->asArray());
                $query = array_merge($query, ['mocean-medium' => self::PL]);
                if (!isset($query['mocean-resp-format'])) {
                    $query['mocean-resp-format'] = 'json';
                }
                $request = $request->withUri($request->getUri()->withQuery(http_build_query($query)));
                break;
        }

        return $request;
    }

    /**
     * Wraps the HTTP Client, creates a new PSR-7 request adding authentication, signatures, etc.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        if ($this->credentials instanceof Basic) {
            $request = self::authRequest($request, $this->credentials);
        }

        $uri = (string) $request->getUri();

        return $this->client->sendRequest(
            $request->withUri(new Uri($this->baseUrl.'/rest/'.$this->version.$uri))
        );
    }

    public function __call($name, $args)
    {
        if (!$this->factory->hasApi($name)) {
            throw new \RuntimeException('no api namespace found: '.$name);
        }

        $collection = $this->factory->getApi($name);

        if (empty($args)) {
            return $collection;
        }

        return call_user_func_array($collection, $args);
    }

    public function __get($name)
    {
        if (!$this->factory->hasApi($name)) {
            throw new \RuntimeException('no api namespace found: '.$name);
        }

        return $this->factory->getApi($name);
    }
}
