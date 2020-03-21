<?php

namespace Crawler\Exception;

use Crawler\Exception\BaseException;

class SocketException extends BaseException {

    public function errorMessage() {
        $errorMessage = 'Error on line '.$this->getLine().
                        ' in '.$this->getFile().
                        ': <b>'.$this->getMessage();
        return $errorMsg;
      }
}