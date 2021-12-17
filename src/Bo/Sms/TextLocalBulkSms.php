<?php

namespace GoApptiv\TextLocal\Bo\Sms;

use Illuminate\Support\Collection;

class TextLocalBulkSms
{
    private $textLocalMessages;
    private $sender;

    /**
     *
     * Bulk SMS BO
     *
     * @param Collection $textLocalMessages Collection of TextLocalMessages Object
     * @param string $sender Sender Id
     *
     */
    public function __construct(Collection $textLocalMessages, string $sender)
    {
        $this->textLocalMessages = $textLocalMessages;
        $this->sender = $sender;
    }

    /**
     * Get the value of textLocalMessages
     */
    public function getTextLocalMessages()
    {
        return $this->textLocalMessages;
    }

    /**
     * Set the value of textLocalMessages
     *
     * @return  self
     */
    public function setTextLocalMessages($textLocalMessages)
    {
        $this->textLocalMessages = $textLocalMessages;

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
}
