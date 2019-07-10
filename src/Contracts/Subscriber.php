<?php


namespace SirSova\Webhooks\Contracts;


interface Subscriber
{
    /**
     * @return string
     */
    public function event(): string;

    /**
     * @return string
     */
    public function url(): string;

    /**
     * @return bool
     */
    public function isEnabled(): bool;
}