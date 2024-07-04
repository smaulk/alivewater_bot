<?php

namespace App\Factories;

use App\Core\Helper;
use App\Dto\CashOperation;
use App\Dto\DeviceDto;
use App\Dto\Sale;
use Exception;

class DeviceDtoFactory
{
    /**
     * @throws Exception
     */
    public static function make(array $data): DeviceDto
    {
        $timezone = $data['Info']['Timezone'];
        $timeLastEncash = intval(
            $data['DeviceState']['LastEncash']['Dts'] / 1000
        );
        $timeLastSale = intval(
            $data['DeviceState']['LastSale']['Dt'] / 1000
        );

        return new DeviceDto(
            $data['Id'],
            $data['Info']['Address'],
            $data['DeviceState']['Coins'],
            $data['Info']['CostPerLiter'],
            new CashOperation(
                Helper::getDate($timeLastEncash, 'Y-m-d H:i', $timezone),
                $data['DeviceState']['LastEncash']['Coins'],
            ),
            new Sale(
                Helper::getDate($timeLastSale, 'Y-m-d H:i', $timezone),
                $data['DeviceState']['LastSale']['Amount'],
                $data['DeviceState']['LastSale']['Volume'],
            )
        );
    }
}