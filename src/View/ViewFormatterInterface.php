<?php

declare(strict_types=1);

namespace App\View;

interface ViewFormatterInterface
{
    public function repondre(string $titre, string $vue, array $data = [], int $code = 200): string;
}