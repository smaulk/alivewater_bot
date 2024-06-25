<?php

namespace App\Managers;

use App\Dto\UserDto;

final class UserManager extends DataManager
{
    protected function directory(): string
    {
        return 'data';
    }

    public function read(): UserDto|null
    {
        $data = $this->readJson();
        return is_null($data)
            ? null
            : (new UserDto())->fromArray($data);
    }

    public function write(UserDto $userDto): void
    {
        $this->writeJson($userDto->toArray());
    }
}