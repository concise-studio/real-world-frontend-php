<?php

namespace RealWorldFrontendPhp\Exception;

use RealWorldFrontendPhp\Core\AppException as AppException;

class AuthException extends AppException
{
    public function __construct($message=null, $code=401, \Exception $previous=null)
    {
        parent::__construct($message, $code, $previous);
    }
}
