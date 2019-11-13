<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 11/5/2019
 * Time: 11:24 AM.
 */

namespace Mocean\Voice;

use Mocean\Client\Exception\Exception;
use Mocean\Model\ArrayAccessTrait;
use Mocean\Model\AsResponse;
use Mocean\Model\ModelInterface;
use Mocean\Model\ObjectAccessTrait;
use Mocean\Model\ResponseTrait;

class Recording implements ModelInterface, AsResponse
{
    use ObjectAccessTrait, ResponseTrait, ArrayAccessTrait;

    protected $recordingBuffer;
    protected $filename;

    public function __construct($recordingBuffer = null, $filename = null)
    {
        $this->recordingBuffer = $recordingBuffer;
        $this->filename = $filename;
    }

    /**
     * @param $responseData
     * @param $version
     *
     * @throws Exception
     *
     * @return Recording
     */
    public static function createFromResponse($responseData, $version)
    {
        $recording = new self();
        $recording->setRawResponseData($responseData)
            ->processResponse($version);

        if (isset($recording['status']) && $recording['status'] !== 0 && $recording['status'] !== '0') {
            throw new Exception($recording['err_msg']);
        }

        return $recording;
    }

    public function getRecordingBuffer()
    {
        return $this->recordingBuffer;
    }

    public function getFilename()
    {
        return $this->filename;
    }
}
