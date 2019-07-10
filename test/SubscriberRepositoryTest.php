<?php


namespace SirSova\Webhooks\Test;


use SirSova\Webhooks\Contracts\Subscriber;
use SirSova\Webhooks\Contracts\SubscriberRepository;

class SubscriberRepositoryTest extends TestCase
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function testCreate(): void 
    {
        $data = $this->randomSubscriberData();
        $subscriber = $this->repository->create($data);
        
        $this->assertEquals($data['event'], $subscriber->event());
        $this->assertEquals($data['url'], $subscriber->url());
        $this->assertEquals($data['enabled'], $subscriber->isEnabled());
        $this->assertInstanceOf(Subscriber::class, $subscriber);
    }
    
    public function testFind(): void 
    {
        $data = $this->randomSubscriberData();
        $id = \DB::table('webhook_subscribers')
           ->insertGetId($data);
        
        $subscriber = $this->repository->find($id);
        
        $this->assertEquals($data['event'], $subscriber->event());
        $this->assertEquals($data['url'], $subscriber->url());
        $this->assertEquals($data['enabled'], $subscriber->isEnabled());        
        $this->assertInstanceOf(Subscriber::class, $subscriber);
    }
    
    public function testFindAllByEvent(): void 
    {
        $event = 'test';
        $data = [
            $this->randomSubscriberData(['event' => $event]),
            $this->randomSubscriberData(['event' => $event]),
            $this->randomSubscriberData(),
            $this->randomSubscriberData(),
            $this->randomSubscriberData(['event' => $event]),
        ];
        
        \DB::table('webhook_subscribers')->insert($data);
        
        $subscribers = $this->repository->findAllByEvent($event);
        $result = array_map(function (Subscriber $subscriber) {
            return ['event' => $subscriber->event(), 'url' => $subscriber->url(), 'enabled' => $subscriber->isEnabled()];
        }, $subscribers->all());
        
        $expected = [$data[0], $data[1], $data[4]];
        $this->assertEquals($expected, $result);
    }
    
    public function testRemove(): void 
    {
        $id = \DB::table('webhook_subscribers')->insertGetId($this->randomSubscriberData());
        
        $result = $this->repository->remove($id);
        $found = $this->repository->find($id);
        
        $this->assertTrue($result);
        $this->assertNull($found);
    }
    
    private function randomSubscriberData(array $data = []): array 
    {
        return array_merge([
            'event'   => $this->faker->word,
            'url'     => $this->faker->url,
            'enabled' => $this->faker->boolean
        ], $data);
    }
}