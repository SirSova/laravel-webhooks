<?php


namespace SirSova\Webhooks\Subscribers;

use Illuminate\Support\Collection;
use SirSova\Webhooks\Contracts\Subscriber;

/**
 * @property Subscriber|DatabaseSubscriber[] $items
 */
class SubscribersCollection extends Collection
{
    /**
     * @return SubscribersCollection
     */
    public function getUrls(): self
    {
        return $this->map(function (Subscriber $subscriber) {
            return $subscriber->url();
        });
    }

    /**
     * @param string $eventName
     *
     * @return SubscribersCollection
     */
    public function filterByEvent(string $eventName): self
    {
        return $this->filter(function (Subscriber $subscriber) use ($eventName) {
            return $subscriber->event() === $eventName;
        });
    }

    /**
     * @return SubscribersCollection
     */
    public function filterEnabled(): self
    {
        return $this->filter(function (Subscriber $subscriber) {
            return $subscriber->isEnabled();
        });
    }
}
