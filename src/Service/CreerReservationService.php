<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use DateTimeImmutable;

final class CreerReservationService
{
    private const DUREE_MAX_HEURES = 4;

    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        private readonly ReservationRepositoryInterface $reservations,
    ) {
    }

    public function executer(CreerReservationDTO $dto): Reservation
    {
        $salle = $this->salles->trouver($dto->salleId);

        if ($salle === null) {
            throw new SalleIndisponibleException("La salle n'existe pas.");
        }

        if (! $salle->active) {
            throw new SalleIndisponibleException('Cette salle ne peut pas être réservée.');
        }

        if ($dto->dateDebut >= $dto->dateFin) {
            throw new SalleIndisponibleException('La date de début doit précéder la date de fin.');
        }

        $dureeEnHeures = ($dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp()) / 3600;

        if ($dureeEnHeures > self::DUREE_MAX_HEURES) {
            throw new SalleIndisponibleException('Une réservation ne peut pas dépasser quatre heures.');
        }

        if ($dto->dateDebut <= new DateTimeImmutable()) {
            throw new SalleIndisponibleException('La réservation doit commencer dans le futur.');
        }

        $conflit = $this->reservations->rechercherConflit(
            $dto->salleId,
            $dto->dateDebut,
            $dto->dateFin
        );

        if ($conflit !== null) {
            throw new SalleIndisponibleException('La salle est indisponible pendant cette période.');
        }

        $reservation = new Reservation([
            'salle_id'    => $dto->salleId,
            'responsable' => $dto->responsable,
            'email'       => $dto->email,
            'motif'       => $dto->motif,
            'date_debut'  => $dto->dateDebut,
            'date_fin'    => $dto->dateFin,
            'statut'      => 'confirmée',
        ]);

        return $this->reservations->enregistrer($reservation);
    }
}