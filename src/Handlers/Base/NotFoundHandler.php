<?php

namespace App\Handlers\Base;

use App\Handlers\Handler;
use App\Contracts\DtoContract;

final readonly class NotFoundHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return true;
    }

    public function process(): void
    {
        $this->telegram->send($this->method, [
            'chat_id'    => $this->fromId,
            'message_id' => $this->messageId,
            'text'       => "Я тебя не понимаю :(\nПопробуй еще раз",
        ]);
    }

    protected function parseDto(DtoContract $dto): void
    {
    }
}