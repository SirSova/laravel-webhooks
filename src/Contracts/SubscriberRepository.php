<?php


namespace SirSova\Webhooks\Contracts;

use Illuminate\Validation\ValidationException;
use SirSova\Webhooks\Subscribers\DatabaseSubscriber;
use SirSova\Webhooks\Subscribers\SubscribersCollection;

interface SubscriberRepository
{
    /**
     * @param $id
     *
     * @return DatabaseSubscriber
     */
    public function find($id): ?DatabaseSubscriber;

    /**
     * @param string $event
     * @param bool   $enabledOnly
     *
     * @return SubscribersCollection
     */
    public function findAllByEvent(string $event, bool $enabledOnly = false): SubscribersCollection;

    /**
     * @param $data
     *
     * @return DatabaseSubscriber
     * @throws ValidationException
     */
    public function create($data): DatabaseSubscriber;

    /**
     * @param $id
     *
     * @return bool
     */
    public function remove($id): bool;
}
