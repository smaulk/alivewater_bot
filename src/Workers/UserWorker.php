<?php

namespace App\Workers;

use App\Core\Curl;
use App\Core\Helper;
use App\Dto\UserDto;
use App\Managers\JsonManager;

final class UserWorker extends Worker
{
    protected function getPath(): string
    {
        return $this->userDto->uid;
    }


    public function getDevices(): array
    {
        $resp = Curl::get($this->getUrl('devices'),[], $this->userDto->auth->token);
        //(new JsonManager(Helper::basePath().'/devices.json'))->writeJson($resp);
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