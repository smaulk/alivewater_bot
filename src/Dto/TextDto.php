<?php

namespace App\Dto;

use App\Interfaces\DtoMessage;

class TextDto implements DtoMessage
{
    public int $fromId;
    public string $data;

    public function __construct(array $body)
    {
        $this->fromId = $body['message']['from']['id'];
        $this->data = $body['message']['text'];
    }
}