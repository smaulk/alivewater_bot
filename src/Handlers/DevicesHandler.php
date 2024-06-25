<?php

namespace App\Handlers;

use App\Enums\State;
use App\Contracts\DtoContract;
use App\Managers\UserManager;
use App\Workers\DevicesWorker;

final readonly class DevicesHandler extends Handler
{

    public static function validate(DtoContract $dto): bool
    {
        return $dto->data === State::Devices->value;
    }

    public function process(): void
    {
        $devices = (new DevicesWorker($this->userManager->read()))->getDevices();
        var_dump($devices);
    }

    protected function parseDto(DtoContract $dto): void
    {
    }
}