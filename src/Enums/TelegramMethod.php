<?php

namespace App\Enums;

enum TelegramMethod: string
{
    case Send = 'sendMessage';
    case Edit = 'editMessageText';
    case SendAnswer = 'answerCallbackQuery';
}
