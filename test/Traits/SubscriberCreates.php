<?php


namespace SirSova\Webhooks\Test\Traits;


use SirSova\Webhooks\Contracts\Subscriber;
use SirSova\Webhooks\Contracts\SubscriberRepository;

trait SubscriberCreates
{
    /**
     * @param array $data
     *
     * @return Subscriber
     */
    public function createRandomSubscriber(array $data = []): Subscriber
    {
        return app(SubscriberRepository::class)->create(array_merge([
            'event'   => $this->faker->word,
            'url'     => $this->faker->url,
            'enabled' => $this->faker->boolean
        ], $data));
    }
}