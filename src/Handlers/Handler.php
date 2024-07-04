<?php

namespace App\Handlers;

use App\Contracts\DtoContract;
use App\Core\Telegram;
use App\Dto\Request\CallbackDto;
use App\Enums\TelegramMethod;
use App\Repositories\UserRepository;

abstract readonly class Handler
{
    protected Telegram $telegram;
    protected TelegramMethod $method;
    protected int $fromId;
    protected int $messageId;
    protected UserRepository $userRepository;


    public function __construct(DtoContract $dto)
    {
        $this->telegram = new Telegram();
        $this->fromId = $dto->fromId;
        $this->messageId = $dto->messageId;
        $this->userRepository = new UserRepository($dto->fromId);
        $this->parseDto($dto);
        $this->setMethod($dto);
    }

    private function setMethod(DtoContract $dto): void
    {
        if ($dto instanceof CallbackDto) {
            $this->telegram->send(TelegramMethod::SendAnswer, [
                'callback_query_id' => $dto->callbackQueryId,
            ]);
            $this->method = TelegramMethod::Edit;

        } else {
            $this->method = TelegramMethod::Send;
        }
    }


    abstract public static function validate(DtoContract $dto): bool;

    abstract public function process(): void;

    abstract protected function parseDto(DtoContract $dto): void;
}