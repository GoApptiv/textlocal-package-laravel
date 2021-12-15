<?php

namespace GoApptiv\TextLocal\Exception;

use Exception;

class InvalidMobileNumberException extends Exception
{
    protected $message = "Invalid Mobile number entered";

    /**
     * Constructor with message
     *
     * @param $message
     * @return InvalidMobileNumberException
     */
    public static function withMessage($message)
    {
        $exception = new InvalidMobileNumberException();
        $exception->message = $message;
        return $exception;
    }
}
