<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;
use App\Workers\UserWorker;

final readonly class DevicesHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::Devices->value;
    }

    public function process(): void
    {
        $devices = (new UserWorker($this->userManager->read()))->getDevices();

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
            'text' => 'Выберите аппарат',
            'reply_markup' => [
                'inline_keyboard' => [
                    ...$this->getButtons($devices),
                ],
            ],
        ]);
    }

    protected function parseDto(DtoContract $dto): void
    {
    }

    private function getButtons(array $devices): array
    {
        $buttons = [];

        foreach ($devices as $id => $address) {
            $buttons[] = [
                [
                    'text' => $address,
                    'callback_data' => State::SelectDevice->value.':'.$id,
                ]
            ];
        }
        return $buttons;
    }
}