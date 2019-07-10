<?php


namespace SirSova\Webhooks;

use Illuminate\Contracts\Bus\Dispatcher;
use SirSova\Webhooks\Contracts\SubscriberRepository;
use SirSova\Webhooks\Contracts\MessageProcessor as ProcessorContract;
use SirSova\Webhooks\Jobs\ProcessWebhook;

class MessageProcessor implements ProcessorContract
{
    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var string|null
     */
    private $queue;
    
    public function __construct(SubscriberRepository $subscriberRepository, Dispatcher $dispatcher, ?string $queue = null)
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->dispatcher = $dispatcher;
        $this->queue = $queue;
    }

    /**
     * @param Message $message
     *
     * @return void
     */
    public function process(Message $message): void
    {
        $subscribers = $this->subscriberRepository->findAllByEvent($message->type(), true);
        
        foreach ($subscribers as $subscriber) {
            $job = new ProcessWebhook(new Webhook($message, $subscriber->url()));
            
            if ($this->queue) {
                $job->onQueue($this->queue);
            }
            
            $this->dispatcher->dispatch($job);
        }
    }
}
