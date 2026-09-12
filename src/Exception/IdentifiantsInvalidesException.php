<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

final class IdentifiantsInvalidesException extends RuntimeException
{

    public function __construct(
        string $message,
        private readonly string $titre = 'Connexion',
        private readonly string $vue = 'auth/connexion',
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
