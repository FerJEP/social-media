<?php

namespace app;

class Messages
{
    private array $messages;
    public const MESSAGE_ERROR = 0;
    public const MESSAGE_SUCCESS = 1;

    public function __construct()
    {
        $this->messages = [];
    }

    public function setMessage(int $type, string $body)
    {
        $this->messages[] = [
            'type' => $type,
            'body' => $body
        ];
    }

    public function getAll()
    {
        return $this->messages;
    }
}
