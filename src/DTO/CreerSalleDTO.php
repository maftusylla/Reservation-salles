<?php

declare(strict_types=1);

namespace App\DTO;

use App\DTO\CreerSalleDTOBuilder;
use App\Validation\SalleValidator;

final class CreerSalleDTO
{
    public function __construct(
        public readonly string $nom,
        public readonly string $batiment,
        public readonly int $capacite,
        public readonly string $type,
        public readonly bool $active,
    ) {
    }

      public static function builder(SalleValidator $validator): CreerSalleDTOBuilder
    {
        return new CreerSalleDTOBuilder($validator);
    }

    public static function depuisTableau(array $data): self
    {
        return new self(
            nom: (string) $data['nom'],
            batiment: (string) $data['batiment'],
            capacite: (int) $data['capacite'],
            type: (string) $data['type'],
            active: (bool) $data['active'],
        );
    }
}
