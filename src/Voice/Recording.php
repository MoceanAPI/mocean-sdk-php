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

    protected $recordingStream;
    protected $filename;

    public function __construct($recordingStream = null, $filename = null)
    {
        $this->recordingStream = $recordingStream;
        $this->filename = $filename;
    }

    /**
     * @param $responseData
     * @param $version
     * @return Recording
     * @throws Exception
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

    /**
     * Save recording to a directory
     *
     * @param null|string $path path to save recording, default to user directory
     * @param null|string $filename default to call-uuid
     * @return false|int
     */
    public function saveTo($path = null, $filename = null)
    {
        if (!$path) {
            $path = $this->getHomeDir();
        }

        if (!$filename) {
            $filename = $this->filename;
        }

        return file_put_contents($path . '/' . $filename, $this->recordingStream);
    }

    /**
     * Send the recording as response
     *
     * @param null|string $filename default to call-uuid
     */
    public function sendAsResponse($filename = null)
    {
        if (!$filename) {
            $filename = $this->filename;
        }

        header('Content-type: application/x-file-to-save');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo $this->recordingStream;
        exit;
    }

    public function getRecordingStream()
    {
        return $this->recordingStream;
    }

    protected function getHomeDir()
    {
        if (isset($_SERVER['HOME'])) {
            $result = $_SERVER['HOME'];
        } else {
            $result = getenv('HOME');
        }

        if (empty($result) && function_exists('exec')) {
            if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
                $result = exec('echo %userprofile%');
            } else {
                $result = exec('echo ~');
            }
        }

        return $result;
    }
}
