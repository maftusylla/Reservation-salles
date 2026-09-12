<?php

declare(strict_types=1);

namespace App\Exception;

use App\Validation\ValidationResult;
use RuntimeException;

final class ValidationEchoueeException extends RuntimeException
{
    public function __construct(
        private readonly ValidationResult $resultat,
        private readonly string $titre="formulaire invalide",
        private readonly string $vue = 'error/validation',
        private readonly array $contexte = [],

    ) {
        parent::__construct('La validation a échoué.');
    }

    public function resultat(): ValidationResult
    {
        return $this->resultat;
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
