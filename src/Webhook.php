<?php


namespace SirSova\Webhooks;


class Webhook
{

    /**
     * @var Message
     */
    private $message;
    /**
     * @var string
     */
    private $url;

    public function __construct(Message $message, string $url)
    {
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * @return array|\JsonSerializable
     */
    public function context()
    {
        return $this->message->context();
    }

    /**
     * @return string
     */
    public function type(): string 
    {
        return $this->message->type();
    }

    /**
     * @return string
     */
    public function url(): string 
    {
        return $this->url;
    }
}