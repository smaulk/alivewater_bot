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
    private StateRepository $stateRepository;

    public static function validate(DtoContract $dto): bool
    {
        return (new StateRepository($dto->fromId))->exists();
    }

    public function __construct(DtoContract $dto)
    {
        parent::__construct($dto);
        $this->stateRepository = new StateRepository($dto->fromId);
    }

    public function process(): void
    {
        $state = $this->stateRepository->get();

        if($state['state'] === State::InputUsername->value)
        {
            $this->sendInputPassword($state['message_id']);
            $this->stateRepository->set(State::InputPassword->value, $state['message_id']);
            $this->userRepository->set(new UserDto($this->input));
        }
        else if($state['state'] === State::InputPassword->value)
        {
            $this->sendSuccessInput($state['message_id']);
            $this->stateRepository->delete();
            $this->userRepository->set(new UserDto(
                $this->userRepository->get()?->username ?? null,
                Helper::encrypt($this->input)
            ));
        }
    }

    private function sendInputPassword(int $messageId): void
    {
        $this->deleteInputMessage();
        $this->telegram->send(TelegramMethod::Edit, [
            'chat_id' => $this->fromId,
            'message_id' => $messageId,
            'text' => 'Введите пароль',
        ]);
    }

    private function sendSuccessInput(int $messageId): void
    {
        $this->deleteInputMessage();
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

    private function deleteInputMessage(): void
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