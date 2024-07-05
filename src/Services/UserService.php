<?php

namespace App\Services;


use App\Dto\DeviceDto;
use App\Factories\DeviceDtoFactory;
use Exception;

final class UserService extends Service
{
    protected function getMainRoute(): string
    {
        return $this->userDto->uuid;
    }

    /**
     * @throws Exception
     */
    public function getDevicesDto(): array
    {
        $resp = $this->api->get($this->getRoute('devices'));
        $devices = [];
        foreach ($resp['devices'] as $device) {
            $devices[] = DeviceDtoFactory::make($device);
        }
        return $devices;
    }


    public function getDevicesId(): array
    {
        $resp = $this->api->get($this->getRoute('devices'));
        $devices = [];
        foreach ($resp['devices'] as $device) {
            $id = $device['Id'];
            $address = $device['Info']['Address'];
            $pos = strpos($address, ',');
            $devices[$id] = substr($address, $pos + 1);
        }

        return $devices;
    }
}