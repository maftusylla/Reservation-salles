<?php


namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

final class AnnulerReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
    ) {
    }

    public function executer(int $id): Reservation
    {
        $reservation = $this->reservations->trouver($id);

        if ($reservation === null) {
            throw new ReservationIntrouvableException('Cette réservation est introuvable.');
        }

        return $this->reservations->annuler($reservation);
    }
}