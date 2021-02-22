<?php

namespace Mocean\Command;

use GuzzleHttp\Psr7\Request;
use Mocean\Client\ClientAwareInterface;
use Mocean\Client\ClientAwareTrait;
use Mocean\Client\Exception\Exception;
use Mocean\Command\Mc\AbstractMc;

class Client implements ClientAwareInterface
{
    use ClientAwareTrait;

    private $action = McAction::AUTO;

    /** @var Commander */
    private $commander;

    public function __construct()
    {
        $this->commander = new Commander();
    }

    /**
     * @param $action action from \Mocean\Command\McAction
     * @return self
     */
    public function doAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function execute($params)
    {
        if (isset($params["mocean-command"]) && !($params["mocean-command"] instanceof McBuilder)) {
            throw new \InvalidArgumentException('commandBuilder must implement `'.McBuilder::class);
        }
		
		if (isset($params["mocean-event-url"])) {
			$this->setEventUrl($params["mocean-event-url"]);
		}
        if (isset($params["mocean-resp-format"])) {
            $this->setResponseFormat($params["mocean-resp-format"]);
        }

        $command = $params["mocean-command"]->build();

        if (count($command) <= 0) {
            throw new Exception('No command found in McBuilder.');
        }

        $this->commander->setCommand($command);

        if ($this->action === McAction::AUTO || $this->action === McAction::SEND_TELEGRAM) {
            $uri = "/send-message";
        }

        $params = $this->commander->getRequestData();

        $request = new Request(
            'POST',
            $uri,
            ['content-type' => 'application/json']
        );

        $request->getBody()->write(json_encode($params));
        $response = $this->client->send($request);

        $response->getBody()->rewind();
        $data = $response->getBody()->getContents();

        return Commander::createFromResponse($data, $this->client->version);
    }

    public function setEventUrl($url)
    {
        $this->commander->setEventUrl($url);
        return $this;
    }
    public function setResponseFormat($type)
    {
        $this->commander->setResponseFormat($type);
        return $this;
    }
}