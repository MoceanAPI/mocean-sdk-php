<?php
/**
 * Mocean Client Library for PHP.
 *
 * @copyright Copyright (c) 2018 Micro Ocean, Inc.
 * @license MIT License
 */

namespace Mocean;

use Http\Client\HttpClient;
use Mocean\Client\Credentials\Basic;
use Mocean\Client\Credentials\Container;
use Mocean\Client\Credentials\CredentialsInterface;
use Mocean\Client\Credentials\Keypair;
use Mocean\Client\Credentials\OAuth;
use Mocean\Client\Credentials\SharedSecret;
use Mocean\Client\Factory\FactoryInterface;
use Mocean\Client\Factory\MapFactory;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Uri;

/**
 * Mocean API Client, allows access to the API from PHP.
 *
 * @property \Mocean\Message\Client $message
 *
 * @method \Mocean\Message\Client message()
 * @method \Mocean\Account\Client account()
 * @method \Mocean\Verify\Client verify()
 * @method \Mocean\NumberLookup\Client numberLookup()
 */
class Client
{
    public $version = '1';
    public $baseUrl = 'https://rest.moceanapi.com';
    const PL = 'PHP-SDK';
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
     */
    public function __construct(CredentialsInterface $credentials, $options = [], HttpClient $client = null)
    {
        if ($client === null) {
            $client = new \Http\Adapter\Guzzle6\Client();
        }

        $this->setHttpClient($client);

        //make sure we know how to use the credentials
        if (!($credentials instanceof Container) && !($credentials instanceof Basic) && !($credentials instanceof SharedSecret) && !($credentials instanceof OAuth)) {
            throw new \RuntimeException('unknown credentials type: '.get_class($credentials));
        }

        $this->credentials = $credentials;

        $this->options = $options;

        if(isset($this->options['baseUrl'])){
            $this->baseUrl = rtrim($this->options['baseUrl'], '/');
        }

        if(isset($this->options['version'])) {
            $this->version = $this->options['version'];
        }

        $this->setFactory(new MapFactory([
            'account'      => 'Mocean\Account\Client',
            'message'      => 'Mocean\Message\Client',
            'verify'       => 'Mocean\Verify\Client',
            'numberLookup' => 'Mocean\NumberLookup\Client',
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

    /**
     * @param RequestInterface $request
     * @param Signature        $signature
     *
     * @return RequestInterface
     */
    public static function signRequest(RequestInterface $request, SharedSecret $credentials)
    {
        switch ($request->getHeaderLine('content-type')) {
            case 'application/json':
                $body = $request->getBody();
                $body->rewind();
                $content = $body->getContents();
                $params = json_decode($content, true);
                $params['api_key'] = $credentials['api_key'];
                $signature = new Signature($params, $credentials['shared_secret']);
                $body->rewind();
                $body->write(json_encode($signature->getSignedParams()));
                break;
            case 'application/x-www-form-urlencoded':
                $body = $request->getBody();
                $body->rewind();
                $content = $body->getContents();
                $params = [];
                parse_str($content, $params);
                $params['api_key'] = $credentials['api_key'];
                $signature = new Signature($params, $credentials['shared_secret']);
                $params = $signature->getSignedParams();
                $body->rewind();
                $body->write(http_build_query($params, null, '&'));
                break;
            default:
                $query = [];
                parse_str($request->getUri()->getQuery(), $query);
                $query['api_key'] = $credentials['api_key'];
                $signature = new Signature($query, $credentials['shared_secret']);
                $request = $request->withUri($request->getUri()->withQuery(http_build_query($signature->getSignedParams())));
                break;
        }

        return $request;
    }

    public static function authRequest(RequestInterface $request, Basic $credentials)
    {
        switch ($request->getHeaderLine('content-type')) {
            case 'application/json':
                $body = $request->getBody();
                $body->rewind();
                $content = $body->getContents();
                $params = json_decode($content, true);
                $params = array_merge($params, $credentials->asArray());
        $params = array_merge($params, ['mocean-medium' => \Mocean\Client::PL]);
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
        $params = array_merge($params, ['mocean-medium' => \Mocean\Client::PL]);
                $body->rewind();
                $body->write(http_build_query($params, null, '&'));
                break;
            default:
                $query = [];
                parse_str($request->getUri()->getQuery(), $query);
                $query = array_merge($query, $credentials->asArray());
        $query = array_merge($query, ['mocean-medium' => \Mocean\Client::PL]);
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
    public function send(\Psr\Http\Message\RequestInterface $request)
    {
        if ($this->credentials instanceof Container) {
            if (strpos($request->getUri()->getPath(), '/v1/calls') === 0) {
                $request = $request->withHeader('Authorization', 'Bearer '.$this->credentials->get(Keypair::class)->generateJwt());
            } else {
                $request = self::authRequest($request, $this->credentials->get(Basic::class));
            }
        } elseif ($this->credentials instanceof Keypair) {
            $request = $request->withHeader('Authorization', 'Bearer '.$this->credentials->get(Keypair::class)->generateJwt());
        } elseif ($this->credentials instanceof SharedSecret) {
            $request = self::signRequest($request, $this->credentials);
        } elseif ($this->credentials instanceof Basic) {
            $request = self::authRequest($request, $this->credentials);
        }

        //todo: add oauth support

        $uri = (string) $request->getUri();

        return $this->client->sendRequest(
            $request->withUri(new Uri($this->baseUrl . '/rest/' . $this->version . $uri))
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
