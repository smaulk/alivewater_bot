<?php
namespace App\Dto;

use App\Interfaces\DtoMessage;

class CallbackDto implements DtoMessage
{
    public int $fromId;
    public int $messageId;
    public string $callbackQueryId;
    public string $data;

    public function __construct(array $body)
    {
        $this->fromId = $body['callback_query']['from']['id'];
        $this->messageId = $body['callback_query']['message']['message_id'];
        $this->callbackQueryId = $body['callback_query']['id'];
        $this->data = $body['callback_query']['data'];
    }
}