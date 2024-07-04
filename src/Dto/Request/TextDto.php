<?php

namespace App\Dto\Request;

use App\Contracts\DtoContract;

readonly class TextDto implements DtoContract
{
    public int $fromId;
    public string $data;
    public int $messageId;

    public function __construct(array $body)
    {
        $this->fromId = $body['message']['from']['id'];
        $this->data = $body['message']['text'];
        $this->messageId = $body['message']['message_id'];
    }
}