<?php

namespace App\Repositories;

use App\Dto\UserDto;
use App\Managers\DataManager;

final class UserRepository extends DataManager
{
    protected function directory(): string
    {
        return 'user';
    }

    public function get(): UserDto|null
    {
        $data = $this->readJson();
        return is_null($data)
            ? null
            : (new UserDto())->fromArray($data);
    }

    public function set(UserDto $userDto): void
    {
        $this->writeJson($userDto->toArray());
    }
}