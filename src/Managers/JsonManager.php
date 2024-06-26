<?php

namespace App\Managers;

class JsonManager
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function readJson(): array|null
    {
        if (file_exists($this->path)) {
            $file = file_get_contents($this->path);
            return json_decode($file, true);
        }
        return null;
    }

    public function writeJson(array $data, bool $append = false): void
    {
        $data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->path, $data, $append ? FILE_APPEND : 0);
    }

    public function delete(): void
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }
}