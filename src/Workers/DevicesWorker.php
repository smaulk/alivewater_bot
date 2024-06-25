<?php

namespace App\Workers;

use App\Core\Curl;
use App\Dto\UserDto;

class DevicesWorker extends Worker
{
    protected function getPath(): string
    {
        return $this->userDto->uid;
    }


    public function getDevices(): string
    {
        $resp = Curl::get($this->getUrl('devices'),[], $this->userDto->auth->token);
        $devices = [];
        foreach ($resp['devices'] as $device) {
            $devices[] = [$device['Info']['Address'] => $device['DeviceState']['Coins'].'тг'];
        }

        return json_encode($devices, JSON_PRETTY_PRINT);
    }

}