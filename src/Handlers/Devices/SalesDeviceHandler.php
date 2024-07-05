<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Dto\SaleDto;
use App\Enums\Currency;
use App\Enums\State;
use App\Handlers\Handler;
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
        $deviceService = new DeviceService($this->userRepository->get(), $this->deviceId);
        $sales = $deviceService->getSalesToday();
        $sumDto = $deviceService->getSumToday();

        $currency = $sumDto->currency;
        $coinsPercent = intval(($sumDto->coins / $sumDto->amount) * 100);
        $mobileAppPercent = 100 - $coinsPercent;

        $text = <<<TEXT
Всего за сегодня $sumDto->amount $currency->value | $sumDto->litres л
Монет: $sumDto->coins $currency->value ($coinsPercent%) QR: $sumDto->mobileApp $currency->value ($mobileAppPercent%)
Продажи:
TEXT;

        foreach ($sales['Sales'] as $sale) {
            $text .= $this->getText($sale, $currency);
        }

        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
            'text' => $text,
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

Тип: $type->value
Дата: $dto->date
Сумма: $dto->amount $currency->value
Литров: $dto->litres

TEXT;

    }
}