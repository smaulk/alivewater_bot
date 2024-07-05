<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Dto\DeviceDto;
use App\Enums\Currency;
use App\Enums\State;
use App\Handlers\Handler;
use App\Services\DevicesService;
use Exception;

final readonly class SelectDeviceHandler extends Handler
{
    private string $deviceId;

    public static function validate(DtoContract $dto): bool
    {
        $state = State::SelectDevice->value;
        return preg_match(
                "/^$state:[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/",
                $dto->data) === 1;
    }

    /**
     * @throws Exception
     */
    public function process(): void
    {
        $deviceDto = (new DevicesService(
            $this->userRepository->get()))
            ->getById($this->deviceId);

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
            'text' => $this->getText($deviceDto),
            'reply_markup' => [
                'inline_keyboard' => [
                    [[
                        'text' => 'Продажи',
                        'callback_data' => State::DeviceSales->value . ':' . $deviceDto->uuid,
                    ]]
                ],
            ],
        ]);
    }

    protected function parseDto(DtoContract $dto): void
    {
        [, $this->deviceId] = explode(':', $dto->data);
    }

    private function getText(DeviceDto $dto): string
    {
        $currency = Currency::get($dto->currency);
        $lastEncahs = $dto->lastEncash;
        $lastSale = $dto->lastSale;
        $saleType = $lastSale->type->value;

        return <<<TEXT
📌Адрес: 
$dto->address
💲Цена за литр: $dto->costPerLiter $currency->value

💰Количество монет: $dto->coins $currency->value
💸Последняя продажа ($saleType):
$lastSale->date - $lastSale->amount $currency->value / $lastSale->litres л

🚚Последняя инкасация:
$lastEncahs->date - $lastEncahs->amount $currency->value
TEXT;
    }
}