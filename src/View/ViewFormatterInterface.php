<?php

declare(strict_types=1);

namespace App\View;

interface ViewFormatterInterface
{
    public function repondre(string $titre, string $vue, array $data = [], int $code = 200): string;

    public function succes(string $url, array $donnees = [], int $code = 200): string;

    public function echecValidation(array $errors, string $titre, string $vue, array $contexte = [], int $code = 422): string;
}