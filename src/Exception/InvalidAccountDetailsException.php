<?php

namespace GoApptiv\TextLocal\Exception;

use Exception;

class InvalidAccountDetailsException extends Exception
{
    protected $message = "Invalid account details entered";

    /**
     * Constructor with message
     *
     * @param $message
     * @return InvalidAccountDetailsException
     */
    public static function withMessage($message)
    {
        $exception = new InvalidAccountDetailsException();
        $exception->message = $message;
        return $exception;
    }
}
