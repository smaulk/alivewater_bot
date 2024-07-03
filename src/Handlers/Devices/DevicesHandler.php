<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;
use App\Repositories\DevicesRepository;
use App\Workers\UserWorker;

final readonly class DevicesHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::Devices->value;
    }

    public function process(): void
    {
        $devicesRepository = new DevicesRepository($this->fromId);
        $devices = $devicesRepository->get();
        if(is_null($devices))
        {
            $devices = (new UserWorker($this->userRepository->get()))->getDevices();
            $devicesRepository->set($devices);
        }

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
        $buttons[] = [
            [
                'text' => 'Обновить',
                'callback_data' => State::SelectDevice->value.':refresh',
            ]
        ];

        return $buttons;
    }
}