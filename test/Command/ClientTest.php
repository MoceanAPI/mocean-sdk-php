<?php

namespace MoceanTest\Command;

use Mocean\Command\Mc;
use Mocean\Command\McBuilder;
use MoceanTest\AbstractTesting;
use Psr\Http\Message\RequestInterface;

class ClientTest extends AbstractTesting
{
    public function testExecute()
    {
        $inputParams = [
            'mocean-event-url'=> 'https://moceanapi.com',
            'mocean-command' => McBuilder::create()
                                            ->add(
                                                Mc::tgSendText()
                                                    ->setFrom("bot_username")
                                                    ->setTo("123456789")
                                                    ->setContent("Hello world")
                                            )
        ];

        $mockHttp = $this->makeMockHttpClient(function (RequestInterface $request) use ($inputParams) {
            $this->assertEquals('POST', $request->getMethod());
            $this->assertEquals($this->getTestUri('/send-message'), $request->getUri()->getPath());
            $body = $this->getContentFromRequest($request);
            $this->assertEquals($inputParams['mocean-event-url'], $body['mocean-event-url']);
            $this->assertEquals(
                Mc::tgSendText()
                ->setFrom("bot_username")
                ->setTo("123456789")
                ->setContent("Hello world")
                ->getRequestData(),

                json_decode($body['mocean-command'], true)[0]
            );

            return $this->getResponse('command.xml');
        });

        $client = $this->makeMoceanClientWithMockHttpClient($mockHttp);

        $commandRes = $client->command()->execute($inputParams);
        $this->assertInstanceOf(\Mocean\Command\Commander::class, $commandRes);
    }
}