<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Dto\SaleDto;
use App\Enums\Currency;
use App\Enums\State;
use App\Handlers\Handler;
use App\Repositories\DevicesRepository;
use App\Services\DeviceService;
use Exception;

final readonly class SalesDeviceHandler extends Handler
{
    private string $deviceId;

    public static function validate(DtoContract $dto): bool
    {
        $state = State::DeviceSales->value;
        return preg_match(
                "/^$state:[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/",
                $dto->data) === 1;
    }

    /**
     * @throws Exception
     */
    public function process(): void
    {
        $address = (new DevicesRepository($this->fromId))->getAddressById($this->deviceId);
        $limit = 10;
        $deviceService = new DeviceService($this->userRepository->get(), $this->deviceId);
        $sales = $deviceService->getSalesToday($limit);
        $sumDto = $deviceService->getSumToday();

        $currency = $sumDto->currency;
        $coinsPercent = intval(($sumDto->coins / $sumDto->amount) * 100);
        $mobileAppPercent = 100 - $coinsPercent;

        $text = <<<TEXT
📌Адрес:
$address

🧮 Всего за сегодня  $sumDto->amount $currency->value | $sumDto->litres л
🪙 Монет: $sumDto->coins $currency->value ($coinsPercent%) 
📱 QR: $sumDto->mobileApp $currency->value ($mobileAppPercent%)

Последние $limit продаж:

TEXT;

        foreach ($sales['Sales'] as $sale) {
            $text .= $this->getText($sale, $currency);
        }

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
            'text' => $text,
            'reply_markup' => [
                'inline_keyboard' => [
                    [[
                        'text' => 'Назад к аппарату',
                        'callback_data' => State::SelectDevice->value . ':' . $this->deviceId,
                    ]]
                ],
            ],
        ]);

    }

    protected function parseDto(DtoContract $dto): void
    {
        [, $this->deviceId] = explode(':', $dto->data);
    }

    private function getText(SaleDto $dto, Currency $currency): string
    {
        $type = $dto->type;
        return <<<TEXT

🛒 Тип: $type->value
📅 Дата: $dto->date
💸 Сумма: $dto->amount $currency->value
⚖️ Объем: $dto->litres л

TEXT;

    }
}