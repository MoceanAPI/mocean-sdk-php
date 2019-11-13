<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/10/2019
 * Time: 5:38 PM.
 */

namespace Mocean\Voice\Mc;

class Play extends AbstractMc
{
    public function setFiles($files)
    {
        $this->requestData['file'] = $files;

        return $this;
    }

    public function addFile($file)
    {
        if (isset($this->requestData['file'])) {
            if (is_array($file)) {
                foreach ($file as $f) {
                    $this->requestData['file'][] = $f;
                }
            } else {
                $this->requestData['file'][] = $file;
            }
        } else {
            $this->requestData['file'] = is_array($file) ? $file : [$file];
        }

        return $this;
    }

    public function setBargeIn($bargeIn)
    {
        $this->requestData['barge-in'] = $bargeIn;

        return $this;
    }

    public function setClearDigitCache($clearDigitCache)
    {
        $this->requestData['clear-digit-cache'] = $clearDigitCache;

        return $this;
    }

    protected function requiredKey()
    {
        return ['file'];
    }

    protected function action()
    {
        return 'play';
    }
}
