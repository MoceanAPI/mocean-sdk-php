<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 11:04 AM.
 */

namespace MoceanTest\Voice;

use Mocean\Voice\Mc;
use Mocean\Voice\McBuilder;
use MoceanTest\AbstractTesting;

class VoiceTest extends AbstractTesting
{
    protected $mockJsonResponseStr;
    protected $mockXmlResponseStr;

    protected $jsonResponse;
    protected $xmlResponse;

    protected function setUp()
    {
        $this->mockJsonResponseStr = $this->getResponseString('voice.json');
        $this->mockXmlResponseStr = $this->getResponseString('voice.xml');

        $this->jsonResponse = \Mocean\Voice\Voice::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion);
        $this->xmlResponse = \Mocean\Voice\Voice::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion);
    }

    public function testRequestDataParams()
    {
        $params = [
            'mocean-resp-format' => 'json',
            'mocean-to'          => 'testing to',
            'mocean-event-url'   => 'testing event url',
            'mocean-command'     => 'testing mocean command',
        ];
        $req = new \Mocean\Voice\Voice('testing to', null, $params);

        $this->assertEquals($params, $req->getRequestData());

        $setterReq = new \Mocean\Voice\Voice('testing to');
        $setterReq->setTo('testing to');
        $setterReq->setEventUrl('testing event url');
        $setterReq->setMoceanCommand('testing mocean command');
        $setterReq->setResponseFormat('json');

        $this->assertEquals($params, $setterReq->getRequestData());
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(\Mocean\Voice\Voice::class, $this->jsonResponse);
        $this->assertInstanceOf(\Mocean\Voice\Voice::class, $this->xmlResponse);
    }

    public function testObjectToStringFunction()
    {
        $this->assertEquals($this->jsonResponse, $this->mockJsonResponseStr);
        $this->assertEquals($this->xmlResponse, $this->mockXmlResponseStr);
    }

    public function testDirectAccessResponseData()
    {
        $this->objectTesting(\Mocean\Voice\Voice::createFromResponse($this->mockJsonResponseStr, $this->defaultVersion));
        $this->objectTesting(\Mocean\Voice\Voice::createFromResponse($this->mockXmlResponseStr, $this->defaultVersion));

        $this->objectTesting($this->jsonResponse);
        $this->objectTesting($this->xmlResponse);
    }

    public function testObjectErrorWhenCreateFromResponseWithStatus0()
    {
        try {
            \Mocean\Voice\Voice::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    public function testSetMc()
    {
        $mc = [
            [
                'file'   => ['https://test.com'],
                'action' => 'play',
            ],
        ];
        $voice = new \Mocean\Voice\Voice('testing to', $mc);
        $this->assertEquals($voice->getRequestData()['mocean-command'], json_encode($mc));

        $mcBuilder = new McBuilder();
        $mcBuilder->add(Mc::play()->setFiles($mc[0]['file']));
        $voice->setMoceanCommand($mcBuilder);
        $this->assertEquals($voice->getRequestData()['mocean-command'], json_encode($mc));

        $playMc = Mc::play()->setFiles($mc[0]['file']);
        $voice->setMoceanCommand($playMc);
        $this->assertEquals($voice->getRequestData()['mocean-command'], json_encode($mc));
    }

    private function objectTesting($res)
    {
        $this->assertEquals($res->calls[0]->status, '0');
        $this->assertEquals($res->calls[0]->{'session-uuid'}, 'xxx-xxx-xxx-xxx');
        $this->assertEquals($res->calls[0]->{'call-uuid'}, 'xxx-xxx-xxx-xxx');

        $this->assertEquals($res['calls'][0]['status'], '0');
        $this->assertEquals($res['calls'][0]['session-uuid'], 'xxx-xxx-xxx-xxx');
        $this->assertEquals($res['calls'][0]['call-uuid'], 'xxx-xxx-xxx-xxx');
    }
}
