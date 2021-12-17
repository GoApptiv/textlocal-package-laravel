<?php

namespace GoApptiv\TextLocal\Bo\Sms;

class TextLocalMessage
{
    private $mobileNumber;
    private $message;

    /**
     *
     * Message BO
     *
     * @param string $mobileNumber Mobile number
     * @param string $message Message
     *
     */
    public function __construct(string $mobileNumber, string $message)
    {
        $this->mobileNumber = $mobileNumber;
        $this->message = $message;
    }

    /**
     * Get the value of mobileNumber
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * Set the value of mobileNumber
     *
     * @return  self
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
