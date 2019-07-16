<?php


namespace SirSova\Webhooks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use SirSova\Webhooks\Contracts\Channel;
use SirSova\Webhooks\Message;
use SirSova\Webhooks\Webhook;

class ProcessWebhook
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var Webhook
     */
    private $webhook;

    public function __construct(Webhook $message)
    {
        $this->webhook = $message;
    }

    /**
     * @param string      $type
     * @param             $context
     * @param string      $url
     * @param string|null $queue
     *
     * @return ProcessWebhook
     */
    public static function queued(string $type, $context, string $url, ?string $queue = null): self
    {
        return static::create($type, $context, $url)->onQueue($queue ?? config('webhooks.webhook-queue'));
    }

    /**
     * @param string $type
     * @param        $context
     * @param string $url
     *
     * @return ProcessWebhook
     */
    public static function create(string $type, $context, string $url): self
    {
        return new static(new Webhook(new Message($type, $context), $url));
    }

    /**
     * @param Channel $channel
     */
    public function handle(Channel $channel): void
    {
        $channel->send($this->webhook);
    }
}
