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
        $deviceDto = (new DeviceService(
            $this->userRepository->get(), $this->uuid))
            ->getInfo();

        $lastEncahs = $deviceDto->lastEncash;
        $lastSale = $deviceDto->lastSale;

        $text = <<<TEXT
📌Адрес: 
$deviceDto->address
💲Цена за литр: $deviceDto->costPerLiter тг.

💰Количество монет: $deviceDto->coins тг.
💸Последняя продажа:
$lastSale->date - $lastSale->amount тг. / $lastSale->volume л

🚚Последняя инкасация:
$lastEncahs->date - $lastEncahs->amount тг.
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