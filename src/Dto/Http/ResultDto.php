<?php

namespace App\Dto\Http;

use App\Contracts\DtoContract;

class ResultDto implements DtoContract
{
    public int $messageId;

    public function __construct(array $body)
    {
        $this->messageId = $body['result']['message_id'];
        //echo json_encode($data);
    }
}