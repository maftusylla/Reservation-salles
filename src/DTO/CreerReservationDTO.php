<?php
declare(strict_types=1);
namespace App\DTO;

use App\Validation\ReservationValidator;


final class CreerReservationDTO
{
    public function __construct(
        public readonly int $salleId,
        public readonly string $responsable,
        public readonly string $email,
        public readonly string $motif,
        public readonly \DateTimeImmutable $dateDebut,
        public readonly \DateTimeImmutable $dateFin,
    ) {
    }
     public static function builder(ReservationValidator $validator): CreerReservationDTOBuilder
    {
        return new CreerReservationDTOBuilder($validator);
    }

    public static function depuisTableau(array $data): self
    {
        return new self(
            salleId: (int) $data['salle_id'],
            responsable: (string) $data['responsable'],
            email: (string) $data['email'],
            motif: (string) $data['motif'],
            dateDebut: new \DateTimeImmutable((string) $data['date_debut']),
            dateFin: new \DateTimeImmutable((string) $data['date_fin']),
        );
    }
}
