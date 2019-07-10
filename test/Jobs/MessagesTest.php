<?php


namespace SirSova\Webhooks\Test\Jobs;

use Illuminate\Contracts\Bus\Dispatcher;
use SirSova\Webhooks\Contracts\SubscriberRepository;
use SirSova\Webhooks\Jobs\ProcessMessage;
use SirSova\Webhooks\Jobs\ProcessWebhook;
use SirSova\Webhooks\Message;
use SirSova\Webhooks\MessageProcessor;
use SirSova\Webhooks\Test\TestCase;
use SirSova\Webhooks\Test\Traits\SubscriberCreates;

class MessagesTest extends TestCase
{
    use SubscriberCreates;
    
    public function testDispatchingMessage(): void
    {
        $message = new Message('test-type', ['foo' => 'bar']);
        $job = new ProcessMessage($message);
        $job->onQueue('test-queue');
        $this->expectsJobs(ProcessMessage::class);

        dispatch($job);
    }
    
    public function testMessageProcessor(): void
    {
        $subscriber = $this->createRandomSubscriber(['enabled' => true]);
        $message = new Message($subscriber->event(), ['foo' => 'bar']);
        
        $this->expectsJobs(ProcessWebhook::class);

        $processor = new MessageProcessor(app(SubscriberRepository::class), app(Dispatcher::class), 'webhook');
        
        $processor->process($message);
    }
}
