<?php

namespace App\Handlers\Auth;


use App\Contracts\DtoContract;
use App\Core\Telegram;
use App\Dto\UserDto;
use App\Enums\State;
use App\Enums\TelegramMethod;
use App\Repositories\UserRepository;
use App\Services\AuthService;

final readonly class Auth
{
    private int $fromId;

    public function __construct(DtoContract $dto)
    {
        $this->fromId = $dto->fromId;
    }

    public function check(): bool
    {
        $userRepository = new UserRepository($this->fromId);
        //Авторизация пользователя
        $userDto = $this->auth($userRepository->get());
        if ($userDto) {
            $userRepository->set($userDto);
            return true;
        }
        $userRepository->delete();
        return false;

    }

    private function auth($userDto): UserDto|null
    {
        return is_null($userDto)
            ? null
            : (new AuthService($userDto))->auth();
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