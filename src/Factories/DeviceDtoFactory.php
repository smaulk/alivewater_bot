<?php

namespace App\Factories;

use App\Core\Helper;
use App\Dto\CashOperation;
use App\Dto\DeviceDto;
use App\Dto\SaleDto;
use App\Enums\SaleType;
use Exception;

class DeviceDtoFactory
{
    /**
     * @throws Exception
     */
    public static function make(array $data): DeviceDto
    {
        $timezone = $data['Info']['Timezone'];
        $timestampLastEncash = intval(
            $data['DeviceState']['LastEncash']['Dts'] / 1000
        );
        $timestampLastSale = intval(
            $data['DeviceState']['LastSale']['Dt'] / 1000
        );

        $saleType = empty($data['DeviceState']['LastSale']['Coins']) ? SaleType::MobileApp : SaleType::Coins;

        return new DeviceDto(
            $data['Id'],
            $data['Info']['Address'],
            $data['Info']['Currency'],
            $data['DeviceState']['Coins'],
            $data['Info']['CostPerLiter'],
            new CashOperation(
                Helper::getDate($timestampLastEncash, 'Y.m.d H:i', $timezone),
                $data['DeviceState']['LastEncash']['Coins'],
            ),
            new SaleDto(
                Helper::getDate($timestampLastSale, 'Y.m.d H:i', $timezone),
                $data['DeviceState']['LastSale']['Amount'],
                $data['DeviceState']['LastSale']['Volume'],
                $saleType,
                $data['Info']['Address'],
            )
        );
    }
}