<?php

namespace App\Handlers\Auth;

use App\Contracts\DtoContract;
use App\Core\Helper;
use App\Dto\UserDto;
use App\Enums\State;
use App\Enums\TelegramMethod;
use App\Handlers\Handler;
use App\Repositories\StateRepository;
use App\Repositories\UserRepository;

final readonly class LoginStateHandler extends Handler
{

    private string $input;
    private UserDto $userDto;
    private StateRepository $stateRepository;

    public static function validate(DtoContract $dto): bool
    {
        return (new StateRepository($dto->fromId))->isState();
    }

    public function __construct(DtoContract $dto)
    {
        parent::__construct($dto);
        $this->stateRepository = new StateRepository($dto->fromId);
        $this->userDto = $this->userRepository->get() ?? new UserDto();
    }

    public function process(): void
    {
        $state = $this->stateRepository->get();

        if($state['state'] === State::InputUsername->value)
            $this->inputUsername($state['message_id']);
        else if($state['state'] === State::InputPassword->value)
            $this->inputPassword($state['message_id']);
        else return;

        (new UserRepository($this->fromId))->set($this->userDto);
    }

    private function inputUsername(int $messageId): void
    {
        $this->userDto->username = $this->input;

        $this->deleteMessage();
        $this->telegram->send(TelegramMethod::Edit, [
            'chat_id' => $this->fromId,
            'message_id' => $messageId,
            'text' => 'Введите пароль',
        ]);

        $this->stateRepository->set(State::InputPassword->value, $messageId);
    }

    private function inputPassword(int $messageId): void
    {
        $this->userDto->password = Helper::encrypt($this->input);
        $this->stateRepository->delete();
        $this->deleteMessage();
        $this->telegram->send(TelegramMethod::Edit, [
            'chat_id' => $this->fromId,
            'message_id' => $messageId,
            'text'    => 'Данные успешно обновлены!',
        ]);
        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'text'    => 'Выберите команду из меню',
            'reply_markup' => [
                'keyboard'          => [
                    [
                        ['text' => State::StartMenu->value],
                    ],
                ],
                'one_time_keyboard' => true,
                'resize_keyboard'   => true,
            ],
        ]);
    }

    private function deleteMessage(): void
    {
        $this->telegram->send(TelegramMethod::Delete, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
        ]);
    }

    protected function parseDto(DtoContract $dto): void
    {
        $this->input = $dto->data;
    }
}