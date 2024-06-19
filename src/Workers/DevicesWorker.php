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


    public function getDevices()
    {
        $resp = Curl::get($this->getUrl('devices'),[], $this->userDto->auth->token);
//        var_dump($resp);
        foreach ($resp->devices as $device) {
            echo $device->Info->Address;
        }
    }

}