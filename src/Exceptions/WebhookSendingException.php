<?php


namespace SirSova\Webhooks\Exceptions;


use GuzzleHttp\Exception\GuzzleException;

class WebhookSendingException extends \RuntimeException implements WebhookException
{
    public function __construct(GuzzleException $previous)
    {
        $message = "Error occurred under sending message: \"{$previous->getMessage()}\"";
        
        parent::__construct($message, $previous->getCode(), $previous);
    }
}