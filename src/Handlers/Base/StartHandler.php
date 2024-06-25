<?php
namespace App\Handlers\Base;

use App\Enums\State;
use App\Handlers\Handler;
use App\Contracts\DtoContract;

final readonly class StartHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === '/start';
    }

    public function process(): void
    {
        $state = is_null($this->userManager->read())
            ? State::Login
            : State::StartMenu;

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'text'    => $this->getText(),
            'reply_markup' => [
                'keyboard'          => [
                    [
                        ['text' => $state->value],
                    ]
                ],
                'one_time_keyboard' => true,
                'resize_keyboard'   => true,
            ],
        ]);
    }

    private function getText(): string
    {
        return <<<TEXT
Привет!
Я помогу отслеживать состояние аппаратов на Alivewater!
Выберите команду из меню
TEXT;
    }

    protected function parseDto(DtoContract $dto): void
    {
    }
}