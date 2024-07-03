<?php

namespace App\Handlers\Devices;

use App\Contracts\DtoContract;
use App\Enums\State;
use App\Handlers\Handler;
use App\Repositories\DevicesRepository;
use App\Workers\UserWorker;

final readonly class RefreshDevicesHandler extends Handler
{
    private DevicesHandler $devicesHandler;

    public function __construct(DtoContract $dto)
    {
        $this->devicesHandler = new DevicesHandler($dto);
        parent::__construct($dto);
    }

    public static function validate(DtoContract $dto): bool
    {
        $state = State::SelectDevice->value;
        return $dto->data === $state.':refresh';
    }

    public function process(): void
    {
        $devices = (new UserWorker($this->userRepository->get()))->getDevices();
        (new DevicesRepository($this->fromId))->set($devices);
        $this->devicesHandler->process();
    }

    protected function parseDto(DtoContract $dto): void
    {
    }

}