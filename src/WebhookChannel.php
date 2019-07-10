<?php


namespace SirSova\Webhooks;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use SirSova\Webhooks\Contracts\Channel;
use SirSova\Webhooks\Exceptions\WebhookSendingException;

class WebhookChannel implements Channel
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Webhook $webhook
     */
    public function send(Webhook $webhook): void 
    {
        $request = new Request('POST', $webhook->url(), $this->defaultHeaders(), json_encode($webhook->context()));
        
        try {
            $response = $this->client->send($request);
        } catch (GuzzleException $exception) {
            throw new WebhookSendingException($exception);
        }
    }

    /**
     * @return array
     */
    protected function defaultHeaders(): array 
    {
        return ['Content-type' => 'application/json'];
    }
}