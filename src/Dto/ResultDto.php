<?php

namespace App\Dto;

readonly class ResultDto
{
    public int $messageId;

    public function __construct(array $body)
    {
        $this->messageId = $body['result']['message_id'];
        //echo json_encode($data);
    }
}