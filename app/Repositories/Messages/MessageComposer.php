<?php

namespace App\Repositories\Messages;

/**
 * Class MessageComposer
 * @package App\Repositories\Messages
 */
class MessageComposer
{

    /**
     * @var
     */
    protected $to;

    /**
     * @var
     */
    protected $from;

    /**
     * @var
     */
    protected $message;

    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * MessageComposer constructor.
     * @param $to
     * @param $from
     * @param $message
     * @param array $attachments
     */
    public function __construct($to, $from, $message, array $attachments = [])
    {
        $this->to = $to;
        $this->from = $from;
        $this->message = $message;
        $this->attachments = $attachments;
    }

    /**
     * @param $url
     * @return $this
     */
    public function addAttachment($url ) : MessageComposer {
        $this->attachments[] = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array {
        $message = [
            'from' => $this->from,
            'body' => $this->message,
        ];

        if ( count( $this->attachments ) && isset( $this->attachments[0] ) ) {
            $message[ 'mediaUrl' ] = $this->attachments[0];
        }

        return $message;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

}
