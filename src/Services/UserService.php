<?php

namespace App\Services;

use App\Core\Api;

final class UserService extends Service
{
    protected function getMainRoute(): string
    {
        return $this->userDto->uuid;
    }

    public function getDevices(): array
    {
        $resp = Api::get($this->getRoute('devices'),[], $this->userDto->auth->token);
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