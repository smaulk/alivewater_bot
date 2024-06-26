<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;

final readonly class SelectDeviceHandler extends Handler
{

    private string $uuid;
    public static function validate(DtoContract $dto): bool
    {
        $state = State::SelectDevice->value;
        return preg_match(
            "/^$state:[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/",
            $dto->data) === 1;
    }

    public function process(): void
    {
        // TODO: Implement process() method.
    }

    protected function parseDto(DtoContract $dto): void
    {
        [, $this->uuid] = explode(':', $dto->data);
    }
}