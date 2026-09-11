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

    public function succes(string $url, array $donnees = [], int $code = 200): string
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        header('Location: ' . $url);

        return json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function echecValidation(array $errors, string $titre, string $vue, array $contexte = [], int $code = 422): string
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');

        return json_encode(['errors' => $errors], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}