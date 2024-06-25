<?php

namespace App\Dto;

use App\Contracts\DtoContract;

class ResultDto implements DtoContract
{
    public int $messageId;

    public function __construct(array $data)
    {
        $this->messageId = $data['result']['message_id'];
        //echo json_encode($data);
    }
}