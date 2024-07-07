<?php

namespace App\Factories;

use App\Core\Helper;
use App\Dto\SaleDto;
use App\Enums\SaleType;

class SaleDtoFactory
{

    public static function make(array $data): SaleDto
    {

        $type = empty($data['Coins']) ? SaleType::MobileApp : SaleType::Coins;

        return new SaleDto(
            $data['DeviceLocalTime'],
            $data['Amount'],
            $data['Litres'],
            $type,
            $data['Address'] ?? null
        );
    }
}