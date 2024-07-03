<?php
namespace App\Managers;

use App\Core\Helper;

abstract class DataManager extends JsonManager
{

    public function __construct(int $userId)
    {
        parent::__construct($this->getPath($userId));
    }

    /**
     * Восзращает директорию, в которой хранятся файлы.
     * @return string
     */
    protected abstract function directory(): string;

    private function getPath(int $userId): string
    {
        return Helper::basePath('data/' . $this->directory()) . '/' . $userId . '.json';
    }
}