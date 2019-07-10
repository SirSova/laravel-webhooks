<?php


namespace SirSova\Webhooks\Subscribers;

use SirSova\Webhooks\Contracts\Subscriber as SubscriberContract;

class Subscriber implements SubscriberContract
{
    /**
     * @var string
     */
    private $event;
    /**
     * @var string
     */
    private $url;
    /**
     * @var bool
     */
    private $enabled;
    
    public function __construct(string $event, string $url, bool $enabled = true)
    {
        $this->event = $event;
        $this->url = $url;
        $this->enabled = $enabled;
    }

    public function event(): string
    {
        return $this->event;
    }
    
    public function url(): string
    {
        return $this->url;
    }
    
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    
    public function toArray(): array
    {
        return [
            'event'   => $this->event(),
            'url'     => $this->url(),
            'enabled' => $this->isEnabled()
        ];
    }
}
