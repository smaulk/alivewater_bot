<?php

namespace App\Services;


final class UserService extends Service
{
    protected function getMainRoute(): string
    {
        return $this->userDto->uuid;
    }

    public function getDevices(): array
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