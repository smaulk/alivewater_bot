<?php

namespace App\Managers;

final class StateManager extends DataManager
{

    protected function directory(): string
    {
        return 'state';
    }

    public function getState(): array|null
    {
        $data = $this->readJson();
        return is_null($data)
            ? null
            : $data;
    }

    public function setState(string $state, $messageId = null): void
    {
        $this->writeJson([
            'state' => $state,
            'message_id' => $messageId,
        ]);
    }

    public function isState(): bool
    {
        return !is_null($this->getState());
    }
}