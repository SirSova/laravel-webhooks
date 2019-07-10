<?php


namespace SirSova\Webhooks\Contracts;


use SirSova\Webhooks\Webhook;

interface Channel
{
    /**
     * @param Webhook $webhook
     */
    public function send(Webhook $webhook): void;
}