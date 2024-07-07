<?php

namespace App\Handlers\Sales;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;

final readonly class SalesHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::Sales->value;
    }

    public function process(): void
    {
        $this->telegram->send($this->method, [
            'chat_id' => $this->fromId,
            'message_id' => $this->messageId,
            'text' => 'Выберите период',
            'reply_markup' => [
                'inline_keyboard' => [
                    [[
                        'text' => 'За сегодня',
                        'callback_data' => State::PeriodSales->value . ':1',
                    ]],
                    [[
                        'text' => 'За неделю',
                        'callback_data' => State::PeriodSales->value . ':7',
                    ]],
                    [[
                        'text' => 'За месяц',
                        'callback_data' => State::PeriodSales->value . ':30',
                    ]],
                ],
            ],
        ]);
    }

    protected function parseDto(DtoContract $dto): void
    {
    }
}