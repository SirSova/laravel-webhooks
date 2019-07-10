<?php


namespace SirSova\Webhooks\Contracts;


use SirSova\Webhooks\Message;

interface MessageProcessor
{
    /**
     * @param Message $message
     *
     * @return void
     */
    public function process(Message $message): void;
}