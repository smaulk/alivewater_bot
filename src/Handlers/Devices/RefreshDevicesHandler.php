<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;
use App\Repositories\DevicesRepository;
use App\Workers\UserWorker;

final readonly class RefreshDevicesHandler extends DevicesHandler
{
    public static function validate(DtoContract $dto): bool
    {
        $state = State::SelectDevice->value;
        return $dto->data === $state . ':refresh';
    }

    protected function getDevices(): array
    {
        $devices = (new UserWorker($this->userRepository->get()))->getDevices();
        (new DevicesRepository($this->fromId))->set($devices);
        return $devices;
    }
}