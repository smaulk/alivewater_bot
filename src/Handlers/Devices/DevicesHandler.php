<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;

abstract readonly class DevicesHandler extends Handler
{
    public function process(): void
    {
        $devices = $this->getDevices();

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
            'text' => 'Выберите аппарат:',
            'reply_markup' => [
                'inline_keyboard' => [
                    ...$this->getButtons($devices),
                ],
            ],
        ]);
    }

    private function getButtons(array $devices): array
    {
        $buttons = [];
        foreach ($devices as $id => $address) {
            $buttons[] = [
                [
                    'text' => $address,
                    'callback_data' => State::SelectDevice->value . ':' . $id,
                ]
            ];
        }
        $buttons[] = [
            [
                'text' => 'Обновить 🔄',
                'callback_data' => State::SelectDevice->value . ':refresh',
            ]
        ];

        return $buttons;
    }

    abstract protected function getDevices(): array;

    protected function parseDto(DtoContract $dto): void
    {
    }
}