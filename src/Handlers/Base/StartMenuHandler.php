<?php

namespace App\Handlers\Base;

use App\Enums\State;
use App\Handlers\Handler;
use App\Contracts\DtoContract;

final readonly class StartMenuHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::StartMenu->value;
    }

    public function process(): void
    {
         $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'text'    => 'Выберите команду из меню',
            'reply_markup' => [
                'keyboard'          => [
                    ...$this->getMenuButtons(),
                ],
                'one_time_keyboard' => true,
                'resize_keyboard'   => true,
            ],
        ]);
    }

    private function getMenuButtons(): array
    {
        return [
            [['text' => State::DeviceList->value]],
            [['text' => State::DevicesInfo->value]],
            [['text' => State::Sales->value]],
        ];
    }

    protected function parseDto(DtoContract $dto): void
    {
    }
}