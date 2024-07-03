<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;
use App\Repositories\DevicesRepository;
use App\Services\UserService;

final readonly class GetDevicesHandler extends DevicesHandler
{
    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::Devices->value;
    }

    protected function getDevices(): array
    {
        $devicesRepository = new DevicesRepository($this->fromId);
        $devices = $devicesRepository->get();
        if (is_null($devices)) {
            $devices = (new UserService($this->userRepository->get()))->getDevices();
            $devicesRepository->set($devices);
        }
        return $devices;
    }
}