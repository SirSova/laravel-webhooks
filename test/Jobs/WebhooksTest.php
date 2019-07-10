<?php


namespace SirSova\Webhooks\Test\Jobs;


use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use SirSova\Webhooks\Jobs\ProcessWebhook;
use SirSova\Webhooks\Message;
use SirSova\Webhooks\Test\TestCase;
use SirSova\Webhooks\Webhook;
use SirSova\Webhooks\WebhookChannel;

class WebhooksTest extends TestCase
{
    public function testDispatchingWebhook(): void 
    {
        $message = new Message($this->faker->word, ['foo' => 'bar']);
        $webhook = new Webhook($message, $this->faker->url);
        $job = new ProcessWebhook($webhook);
        $job->onQueue('test-queue');
        $this->expectsJobs(ProcessWebhook::class);

        dispatch($job);
    }
    
    public function testChannelSending(): void 
    {
        $messageData = ['foo' => 'bar'];
        $message = new Message($this->faker->word, $messageData);
        $webhook = new Webhook($message, $this->faker->url);
        $client = $this->createMock(ClientInterface::class);
        $channel = new WebhookChannel($client);
        
        $client->expects($this->once())
               ->method('send')
               ->with($this->callback(function (RequestInterface $request) use ($messageData) {
                   return json_decode($request->getBody(), true) === $messageData;
               }));
        
        $channel->send($webhook);
    }
}