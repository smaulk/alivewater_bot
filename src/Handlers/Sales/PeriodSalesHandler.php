<?php

namespace App\Handlers\Sales;

use App\Contracts\DtoContract;
use App\Dto\SaleDto;
use App\Enums\Currency;
use App\Enums\State;
use App\Handlers\Handler;
use App\Services\UserService;
use Exception;

final readonly class PeriodSalesHandler extends Handler
{

    private int $period;
    private ?string $next;

    public static function validate(DtoContract $dto): bool
    {
        $state = State::PeriodSales->value;
        return preg_match(
                "/^$state:(\d+)(?::([a-zA-Z\d]+))?$/",
                $dto->data) === 1;
    }

    /**
     * @throws Exception
     */
    public function process(): void
    {
        $userService = new UserService($this->userRepository->get());
        $sales = $userService->getSales($this->period, $this->next);
        $sumDto = $userService->getSum($this->period);

        $next = $sales['Next'];

        $currency = $sumDto->currency;
        $coinsPercent = intval(($sumDto->coins / $sumDto->amount) * 100);
        $mobileAppPercent = 100 - $coinsPercent;

        $text = <<<TEXT
🧮 Всего за $this->period дней  $sumDto->amount $currency->value | $sumDto->litres л
🪙 Монет: $sumDto->coins $currency->value ($coinsPercent%) 
📱 QR: $sumDto->mobileApp $currency->value ($mobileAppPercent%)

Продажи:

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
                        'text' => 'Следующие',
                        'callback_data' => State::PeriodSales->value . ':' . $this->period . ':' . $next
                    ]],
                    [[
                        'text' => 'Вернуться',
                        'callback_data' => State::Sales->value,
                    ]]
                ],
            ],
        ]);

    }

    protected function parseDto(DtoContract $dto): void
    {
        $data = explode(':', $dto->data);
        $this->period = $data[1];
         $this->next = $data[2] ?? null;
    }

    private function getText(SaleDto $dto, Currency $currency): string
    {
        $type = $dto->type;
        return <<<TEXT

📌Адрес: $dto->address
🛒 Тип: $type->value
📅 Дата: $dto->date
💸 Сумма: $dto->amount $currency->value
⚖️ Объем: $dto->litres л

TEXT;

    }
}