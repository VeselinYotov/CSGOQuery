<?php

namespace Crawler\Exception;

abstract class BaseException extends \Exception {

    public function showMesage($errorMessage)
    {
        return $errorMessage;
    }
}