<?php

namespace App\Workers;

use App\Core\Curl;
use App\Dto\UserDto;

final class DeviceWorker extends Worker
{

    private string $uuid;

    public function __construct(UserDto $dto, string $uuid)
    {
        $this->uuid = $uuid;
        parent::__construct($dto);
    }

    protected function getPath(): string
    {
        return 'devices/' . $this->uuid;
    }

    public function getInfo ()
    {
        $resp = Curl::get($this->getUrl(), [], $this->userDto->auth->token);
        $device = [];
        $device['Coins'] = $resp['DeviceState']['Coins'];
        $device['Address'] = $resp['Info']['Address'];
        $date = substr($resp['DeviceState']['LastEncash']['Dts'],0, -3);
        $device['LastEncash']['Date'] = date("Y-m-d H:i:s", $date);
        $device['LastEncash']['Coins'] = $resp['DeviceState']['LastEncash']['Coins'];

        return $device;
    }
}