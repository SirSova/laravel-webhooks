<?php


namespace SirSova\Webhooks\Subscribers;

use DateTimeInterface;
use SirSova\Webhooks\Contracts\Subscriber as SubscriberContract;

class DatabaseSubscriber implements SubscriberContract
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var SubscriberContract
     */
    private $subscriber;
    /**
     * @var DateTimeInterface|null
     */
    private $createdAt;
    /**
     * @var DateTimeInterface|null
     */
    private $updatedAt;

    public function __construct(
        SubscriberContract $subscriber,
        int $id,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $updatedAt = null
    ) {
        $this->id = $id;
        $this->subscriber = $subscriber;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param int                    $id
     * @param string                 $event
     * @param string                 $url
     * @param bool                   $enabled
     * @param DateTimeInterface|null $createdAt
     * @param DateTimeInterface|null $updatedAt
     *
     * @return DatabaseSubscriber
     */
    public static function create(
        int $id,
        string $event,
        string $url,
        bool $enabled = true,
        ?DateTimeInterface $createdAt = null,
        ?DateTimeInterface $updatedAt = null
    ): self {
        return new static (new Subscriber($event, $url, $enabled), $id, $createdAt, $updatedAt);
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return $this->subscriber->event();
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return $this->subscriber->url();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->subscriber->isEnabled();
    }

    /**
     * @return DateTimeInterface|null
     */
    public function createdAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function updatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->id(),
            'event'      => $this->event(),
            'url'        => $this->url(),
            'enabled'    => $this->isEnabled(),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt()
        ];
    }
}
