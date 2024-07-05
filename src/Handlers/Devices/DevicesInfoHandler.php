<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Dto\DeviceDto;
use App\Enums\Currency;
use App\Enums\SaleType;
use App\Enums\State;
use App\Handlers\Handler;
use App\Services\UserService;
use Exception;

final readonly class DevicesInfoHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::DevicesInfo->value;
    }

    /**
     * @throws Exception
     */
    public function process(): void
    {
        $devices = (new UserService($this->userRepository->get()))->getDevicesDto();

        $text = '';
        foreach ($devices as $device)
        {
            $text .= $this->getText($device);
        }

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'text' => $text
        ]);



    }

    protected function parseDto(DtoContract $dto): void
    {
    }

    private function getText(DeviceDto $dto): string
    {
        $lastSale = $dto->lastSale;
        $currency = Currency::get($dto->currency);
        $saleType = $lastSale->type->value;
        return <<<TEXT

📌Адрес: 
$dto->address
💰Количество монет: $dto->coins $currency->value
💸Последняя продажа ($saleType):
$lastSale->date - $lastSale->amount $currency->value / $lastSale->litres л

TEXT;
    }
}