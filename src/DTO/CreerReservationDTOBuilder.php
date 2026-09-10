<?php

declare(strict_types=1);

namespace App\DTO;

use App\Exception\ValidationEchoueeException;
use App\Validation\ReservationValidator;



final class CreerReservationDTOBuilder
{
    private array $data = [];

    public function __construct(
        private readonly ReservationValidator $validator
    ) {
    }

    public function avecSalleId(mixed $salleId): self
    {
        $this->data['salle_id'] = $salleId;

        return $this;
    }

    public function avecResponsable(string $responsable): self
    {
        $this->data['responsable'] = $responsable;

        return $this;
    }

    public function avecEmail(string $email): self
    {
        $this->data['email'] = $email;

        return $this;
    }

    public function avecMotif(string $motif): self
    {
        $this->data['motif'] = $motif;

        return $this;
    }
    
    public function avecDateDebut(string $dateDebut): self
    {
        $this->data['date_debut'] = $dateDebut;

        return $this;
    }

    public function avecDateFin(string $dateFin): self
    {
        $this->data['date_fin'] = $dateFin;

        return $this;
    }

    /**
     * @throws ValidationEchoueeException
     */
    public function build(): CreerReservationDTO
    {
            $resultat = $this->validator->validate($this->data);
        if (! $resultat->isValid()) {
            throw new ValidationEchoueeException($resultat);
        }

        return CreerReservationDTO::depuisTableau($resultat->data());
    }
}
