<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

final class EmailDejaUtiliseException extends RuntimeException
{

    public function __construct(
        string $message,
        private readonly string $titre = 'Inscription',
        private readonly string $vue = 'auth/inscription',
        private readonly array $contexte = [],
    ) {
        parent::__construct($message);
    }

    public function titre(): string
    {
        return $this->titre;
    }

    public function vue(): string
    {
        return $this->vue;
    }

    public function contexte(): array
    {
        return $this->contexte;
    }
}
