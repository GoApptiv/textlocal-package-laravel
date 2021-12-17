<?php

namespace GoApptiv\TextLocal\Bo\Sms;

class TextLocalMessage
{
    private $mobileNumber;
    private $referenceId;
    private $message;

    /**
     *
     * Message BO
     *
     * @param string $mobileNumber Mobile number
     * @param string $referenceId Unique Reference Id to track the message
    * @param string $message Message - Only for Bulk Message
     *
     */
    public function __construct(string $mobileNumber, string $referenceId, string $message = null)
    {
        $this->mobileNumber = $mobileNumber;
        $this->message = $message;
        $this->referenceId = $referenceId;
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
     * Get the value of referenceId
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * Set the value of referenceId
     *
     * @return  self
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;

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
