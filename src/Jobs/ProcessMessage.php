<?php


namespace SirSova\Webhooks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use SirSova\Webhooks\Contracts\MessageProcessor;
use SirSova\Webhooks\Message;

class ProcessMessage
{
    use Dispatchable, InteractsWithQueue, Queueable;
    
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param string      $type
     * @param             $context
     * @param string|null $queue
     *
     * @return ProcessMessage
     */
    public static function queue(string $type, $context, ?string $queue = null): self
    {
        return static::create($type, $context)->onQueue($queue ?? config('webhooks.message-queue'));
    }

    /**
     * @param string $type
     * @param        $context
     *
     * @return ProcessMessage
     */
    public static function create(string $type, $context): self
    {
        return new static(new Message($type, $context));
    }

    /**
     * @param MessageProcessor $messageProcessor
     */
    public function handle(MessageProcessor $messageProcessor): void
    {
        $messageProcessor->process($this->message);
    }
}
