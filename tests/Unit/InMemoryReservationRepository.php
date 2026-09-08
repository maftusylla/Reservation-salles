<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use DateTimeImmutable;

final class InMemoryReservationRepository implements ReservationRepositoryInterface
{
    private array $reservations = [];
    private int $prochainId = 1;

    public function lister(): array
    {
        return array_values($this->reservations);
    }

    public function trouver(int $id): ?Reservation
    {
        return $this->reservations[$id] ?? null;
    }

    public function rechercherConflit(int $salleId, DateTimeImmutable $dateDebut, DateTimeImmutable $dateFin): ?Reservation
    {
        foreach ($this->reservations as $reservation) {
            if ($reservation->salle_id !== $salleId || $reservation->statut !== 'confirmée') {
                continue;
            }

            if ($dateDebut < $reservation->date_fin && $dateFin > $reservation->date_debut) {
                return $reservation;
            }
        }

        return null;
    }

    public function enregistrer(Reservation $reservation): Reservation
    {
        if ($reservation->id === null) {
            $reservation->id = $this->prochainId++;
        }

        $this->reservations[$reservation->id] = $reservation;

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulée';
        $this->reservations[$reservation->id] = $reservation;

        return $reservation;
    }
}