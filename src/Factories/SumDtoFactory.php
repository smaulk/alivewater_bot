<?php

namespace App\Factories;

use App\Dto\SumDto;
use App\Enums\Currency;

class SumDtoFactory
{

    public static function make(array $data): SumDto
    {
        return new SumDto(
            Currency::get($data['Currency']['Code']),
            $data['Amount'],
            $data['Coins'],
            $data['MobileApp'],
            $data['Litres'],
        );
    }
}