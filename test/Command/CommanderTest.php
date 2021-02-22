<?php

namespace MoceanTest\Command;

use MoceanTest\AbstractTesting;
use Mocean\Command\Commander;

class CommanderTest extends AbstractTesting
{
    public function testRequestDataParams()
    {
        $params = [
            'mocean-event-url'   => 'testing event url',
            'mocean-command'     => 'testing mocean command',
            'mocean-resp-format'     => 'json',
        ];


        $setterReq = new Commander();
        $setterReq->setEventUrl('testing event url');
        $setterReq->setCommand('testing mocean command');
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

}