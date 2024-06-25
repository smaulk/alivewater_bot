<?php

namespace App\Handlers\Auth;


use App\Core\Telegram;
use App\Dto\UserDto;
use App\Enums\State;
use App\Enums\TelegramMethod;
use App\Contracts\DtoContract;
use App\Managers\UserManager;
use App\Workers\AuthWorker;

final readonly class Auth
{
    private int $fromId;

    public function __construct(DtoContract $dto)
    {
        $this->fromId = $dto->fromId;
    }

    public function check(): bool
    {
        $userManager = new UserManager($this->fromId);
        //Авторизация пользователя
        $userDto = $this->auth($userManager->read());
        if ($userDto) {
            $userManager->write($userDto);
            return true;
        }
        $userManager->delete();
        return false;

    }

    private function auth($userDto): UserDto|null
    {
        return is_null($userDto)
            ? null
            : (new AuthWorker($userDto))->auth();
    }

    public function sendError(): void
    {
        (new Telegram())->send(TelegramMethod::Send, [
            'chat_id' => $this->fromId,
            'text' => 'Не удалось войти в аккаунт!',
            'reply_markup' => [
                'keyboard' => [
                    [
                        [
                            'text' => State::Login->value,
                        ],
                    ],
                ],
                'one_time_keyboard' => true,
                'resize_keyboard' => true,
            ],
        ]);
    }
}