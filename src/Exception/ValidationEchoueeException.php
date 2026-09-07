<?php

declare(strict_types=1);

namespace App\Exception;

use App\Validation\ValidationResult;
use RuntimeException;

final class ValidationEchoueeException extends RuntimeException
{
    public function __construct(
        private readonly ValidationResult $resultat,
    ) {
        parent::__construct('La validation a échoué.');
    }

    public function resultat(): ValidationResult
    {
        return $this->resultat;
    }
}
