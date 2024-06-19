<?php
namespace App\Handlers;

use App\Interfaces\DtoMessage;
use App\Enums\State;
use App\Handler;

final readonly class StartHandler extends Handler
{

    public static function validate(DtoMessage $dto): bool
    {
        return $dto->data === '/start';
    }

    public function process(): void
    {
        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'text'    => $this->getText(),
            'reply_markup' => [
                'keyboard'          => [
                    [
                        ['text' => State::Start->value],
                    ],
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

    protected function parseDto(DtoMessage $dto): void
    {
    }
}