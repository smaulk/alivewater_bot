<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;
use App\Repositories\DevicesRepository;
use App\Services\UserService;

final readonly class RefreshDevicesListHandler extends DevicesListHandler
{
    public static function validate(DtoContract $dto): bool
    {
        $state = State::SelectDevice->value;
        return $dto->data === $state . ':refresh';
    }

    protected function getDevices(): array
    {
        $devices = (new UserService($this->userRepository->get()))->getDevicesId();
        (new DevicesRepository($this->fromId))->set($devices);
        return $devices;
    }
}