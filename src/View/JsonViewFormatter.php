<?php

declare(strict_types=1);

namespace App\View;

final class JsonViewFormatter implements ViewFormatterInterface
{
    public function repondre(string $titre, string $vue, array $data = [], int $code = 200): string
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}