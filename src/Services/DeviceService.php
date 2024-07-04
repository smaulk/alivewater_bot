<?php

namespace App\Services;

use App\Core\Api;
use App\Core\Helper;
use App\Dto\DeviceDto;
use App\Dto\UserDto;
use App\Factories\DeviceDtoFactory;
use Exception;

final class DeviceService extends Service
{
    private string $deviceId;

    public function __construct(UserDto $dto, string $deviceId)
    {
        $this->deviceId = $deviceId;
        parent::__construct($dto);
    }

    protected function getMainRoute(): string
    {
        return 'devices/' . $this->deviceId;
    }

    /**
     * @throws Exception
     */
    public function getInfo(): DeviceDto
    {
        $resp = $this->api->get($this->getRoute());
        return DeviceDtoFactory::make($resp);
    }
}