<?php

namespace App\Exception;

use Throwable;

class StudentException extends \Exception
{
    public function __construct($message = 'Student Exception', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
