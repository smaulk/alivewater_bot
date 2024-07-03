<?php

namespace App\Repositories;

use App\Managers\DataManager;

final class StateRepository extends DataManager
{

    protected function directory(): string
    {
        return 'state';
    }

    public function get(): array|null
    {
        return $this->readJson();
    }

    public function set(string $state, $messageId = null): void
    {
        $this->writeJson([
            'state' => $state,
            'message_id' => $messageId,
        ]);
    }

    public function isState(): bool
    {
        return !is_null($this->get());
    }
}