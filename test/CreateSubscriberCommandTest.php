<?php


namespace SirSova\Webhooks\Test;


use SirSova\Webhooks\Contracts\SubscriberRepository;
use SirSova\Webhooks\Subscribers\Subscriber;

class CreateSubscriberCommandTest extends TestCase
{

    /**
     * @var SubscriberRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->get(SubscriberRepository::class);
    }

    /**
     * @dataProvider subscriberProvider
     * 
     * @param string $event
     * @param string $url
     * @param bool   $enabled
     */
    public function testCreate(string $event, string $url, bool $enabled): void 
    {
        $params = [
            'event' => $event,
            'url'   => $url,
        ];
        
        if ($enabled === false) {
            $params['--disabled'] = true;
        }
        
        $command = $this->artisan('webhooks:subscriber', $params);
        $command->assertExitCode(0);
        $command->execute();
        
        /** @var Subscriber $subscriber */
        $subscriber = $this->repository->findAllByEvent($event)->all()[0];
        
        $this->assertEquals($subscriber->event(), $event);
        $this->assertEquals($subscriber->url(), $url);
        $this->assertEquals($subscriber->isEnabled(), $enabled);
    }

    /**
     * @return array
     */
    public function subscriberProvider(): array
    {
        return [
            ['event1', 'http://url1.com', false],
            ['event2', 'http://url2.com', true],
            ['event3', 'http://url3.com', true],
            ['event4', 'http://url4.com', false]
        ];
    }
    
    public function testNotValidUrl(): void 
    {
        $command = $this->artisan('webhooks:subscriber', [
            'event' => 'event',
            'url'   => 'wrong-url'
        ]);
        $command->assertExitCode(1);

        $command->execute();
    }
}