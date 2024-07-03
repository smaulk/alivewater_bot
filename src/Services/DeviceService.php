<?php

namespace App\Services;

use App\Core\Api;
use App\Core\Helper;
use App\Dto\UserDto;
use Exception;

final class DeviceService extends Service
{
    private string $deviceId;

    public function __construct(UserDto $dto, string $deviceUuid)
    {
        $this->deviceId = $deviceUuid;
        parent::__construct($dto);
    }

    protected function getMainRoute(): string
    {
        return 'devices/' . $this->deviceId;
    }

    /**
     * @throws Exception
     */
    public function getInfo(): array
    {
        $resp = Api::get($this->getRoute(), [], $this->userDto->auth->token);
        $timezone = $resp['Info']['Timezone'];
        $device['Coins'] = $resp['DeviceState']['Coins'];
        $device['Address'] = $resp['Info']['Address'];
        $timeEncash = $resp['DeviceState']['LastEncash']['Dts'];
        $timeEncash = intval($timeEncash / 1000);
        $device['LastEncash']['Date'] = Helper::getDate($timeEncash, 'Y-m-d H:i', $timezone);

        $device['LastEncash']['Coins'] = $resp['DeviceState']['LastEncash']['Coins'];

        return $device;
    }
}