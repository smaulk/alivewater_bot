<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;
use App\Services\DeviceService;

final readonly class SelectDeviceHandler extends Handler
{
    private string $uuid;

    public static function validate(DtoContract $dto): bool
    {
        $state = State::SelectDevice->value;
        return preg_match(
                "/^$state:[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/",
                $dto->data) === 1;
    }

    /**
     * @throws \Exception
     */
    public function process(): void
    {
        $device = (new DeviceService($this->userRepository->get(), $this->uuid))->getInfo();

        $address = $device['Address'];
        $coins = $device['Coins'];
        $encashDate = $device['LastEncash']['Date'];
        $encashCoins = $device['LastEncash']['Coins'];

        $text = <<<TEXT
Адрес: $address
Количество монет: $coins тг.
Последняя инкасация:
$encashDate - $encashCoins тг.
TEXT;

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
            'text' => $text,
        ]);
    }

    protected function parseDto(DtoContract $dto): void
    {
        [, $this->uuid] = explode(':', $dto->data);
    }
}