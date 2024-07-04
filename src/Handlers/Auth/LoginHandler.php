<?php

namespace App\Handlers\Auth;

use App\Contracts\DtoContract;
use App\Dto\ResultDto;
use App\Enums\State;
use App\Handlers\Handler;
use App\Repositories\StateRepository;

final readonly class LoginHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::Login->value;
    }

    public function process(): void
    {
        $resp = $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'text'    => 'Введите имя пользователя',
        ]);
        $resp = new ResultDto($resp);
        (new StateRepository($this->fromId))->set(State::InputUsername->value, $resp->messageId);
    }

    protected function parseDto(DtoContract $dto): void
    {
    }
}