<?php

namespace App\Repositories;

use App\Managers\DataManager;

final class DevicesRepository extends DataManager
{

    protected function directory(): string
    {
        return 'devices';
    }

    public function get(): array|null
    {
        return $this->readJson();
    }

    public function set(array $data): void
    {
        $this->writeJson($data);
    }
}