<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/11/2019
 * Time: 11:04 AM.
 */

namespace MoceanTest\Voice;

use Mocean\Voice\Recording;
use MoceanTest\AbstractTesting;

class RecordingTest extends AbstractTesting
{
    public function testGetter()
    {
        $bufferData = 'data';
        $filename = 'abc.mp3';

        $recording = new Recording($bufferData, $filename);

        $this->assertEquals($recording->getRecordingBuffer(), $bufferData);
        $this->assertEquals($recording->getFilename(), $filename);
    }

    public function testObjectErrorWhenCreateFromResponseWithStatus0()
    {
        try {
            \Mocean\Voice\Recording::createFromResponse($this->getResponseString('error_response.json'), $this->defaultVersion);
            $this->fail();
        } catch (\Mocean\Client\Exception\Exception $e) {
        }
    }

    public function testObjectCreateFromResponse()
    {
        $this->assertInstanceOf(
            \Mocean\Voice\Recording::class,
            \Mocean\Voice\Recording::createFromResponse($this->getResponseString('recording.json'), $this->defaultVersion)
        );
    }
}
