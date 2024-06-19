<?php

namespace App\Handlers;

use App\Core\DataManager;
use App\Dto\UserDto;
use App\Enums\State;
use App\Handler;
use App\Interfaces\DtoMessage;
use App\Workers\AuthWorker;
use App\Workers\DevicesWorker;

final readonly class DevicesHandler extends Handler
{

    public static function validate(DtoMessage $dto): bool
    {
        return $dto->data === State::Devices->value;
    }

    public function process(): void
    {
        $userDto = (new AuthWorker(DataManager::readUserData($this->fromId)))->auth();
        DataManager::writeUserData($this->fromId, $userDto);
        $devices = (new DevicesWorker($userDto))->getDevices();
        var_dump($devices);
    }

    protected function parseDto(DtoMessage $dto): void
    {
        // TODO: Implement parseDto() method.
    }


}