<?php


namespace SirSova\Webhooks;


class Message
{

    /**
     * @var string
     */
    private $type;

    /**
     * @var array|\JsonSerializable
     */
    private $context;
    
    public function __construct(string $type, $context)
    {
        $this->type = $type;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function type(): string 
    {
        return $this->type;
    }

    /**
     * @return array|\JsonSerializable
     */
    public function context()
    {
        return $this->context;
    }
}