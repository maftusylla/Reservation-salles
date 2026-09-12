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
        public function listerPagine(?int $salleId, int $page, int $parPage = 10): array
    {
        $reservations = array_values($this->reservations);

        if ($salleId !== null) {
            $reservations = array_values(array_filter(
                $reservations,
                static fn (Reservation $r) => $r->salle_id === $salleId
            ));
        }

        $total        = count($reservations);
        $dernierePage = max(1, (int) ceil($total / $parPage));
        $items        = array_slice($reservations, ($page - 1) * $parPage, $parPage);

        return [
            'items'         => $items,
            'page_actuelle' => $page,
            'derniere_page' => $dernierePage,
            'total'         => $total,
        ];
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