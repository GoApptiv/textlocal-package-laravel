<?php

namespace GoApptiv\TextLocal\Bo\Sms;

use Illuminate\Support\Collection;

class TextLocalSms
{
    private $mobileNumbers;
    private $sender;
    private $message;

    /**
     *
     * SMS BO
     *
     * @param Collection $mobileNumbers Collection of TextLocalMessages Object
     * @param string $sender Sender Id
     * @param string $message Message
     *
     */
    public function __construct(Collection $mobileNumbers, string $sender, string $message)
    {
        $this->mobileNumbers = $mobileNumbers;
        $this->sender = $sender;
        $this->message = $message;
    }



    /**
     * Get the value of mobileNumbers
     */
    public function getMobileNumbers()
    {
        return $this->mobileNumbers;
    }

    /**
     * Set the value of mobileNumbers
     *
     * @return  self
     */
    public function setMobileNumbers($mobileNumbers)
    {
        $this->mobileNumbers = $mobileNumbers;

        return $this;
    }

    /**
     * Get the value of sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set the value of sender
     *
     * @return  self
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

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
