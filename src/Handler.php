<?php

namespace App;

use App\Core\DataManager;
use App\Core\Env;
use App\Core\Telegram;
use App\Dto\CallbackDto;
use App\Enums\TelegramMethod;
use App\Interfaces\DtoMessage;
use App\Workers\AuthWorker;

abstract readonly class Handler
{
    protected Telegram $telegram;
    protected TelegramMethod $method;
    protected int $fromId;
    protected ?int $messageId;

    public function __construct(DtoMessage $dto)
    {
        $this->telegram = new Telegram();
        $this->fromId = $dto->fromId;

        if ($dto instanceof CallbackDto)
        {
            $this->telegram->send(TelegramMethod::SendAnswer, [
                'callback_query_id' => $dto->callbackQueryId,
            ]);
            $this->method = TelegramMethod::Edit;
            $this->messageId = $dto->messageId;
        }
        else
        {
            $this->method = TelegramMethod::Send;
            $this->messageId = null;
        }
    }

    abstract public static function validate(DtoMessage $dto): bool;

    abstract public function process(): void;

    abstract protected function parseDto(DtoMessage $dto): void;
}