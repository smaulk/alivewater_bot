<?php

namespace App\Services;

use App\Core\Api;
use App\Core\Helper;
use App\Dto\DeviceDto;
use App\Dto\UserDto;
use App\Factories\DeviceDtoFactory;
use Exception;

final class DevicesService extends Service
{

    protected function getMainRoute(): string
    {
        return 'devices';
    }

    /**
     * @throws Exception
     */
    public function getById(string $deviceId): DeviceDto
    {
        $resp = $this->api->get($this->getRoute($deviceId));
        return DeviceDtoFactory::make($resp);
    }
}