<?php


namespace App\Repository;

use App\Model\Reservation;
use DateTimeImmutable;

interface ReservationRepositoryInterface
{
    /**
     * @return Reservation[]
     */
    public function lister(): array;
    
    public function listerPagine(?int $salleId, int $page, int $parPage = 10): array;

    public function trouver(int $id): ?Reservation;

    public function rechercherConflit(
        int $salleId,
        DateTimeImmutable $dateDebut,
        DateTimeImmutable $dateFin
    ): ?Reservation;

    public function enregistrer(Reservation $reservation): Reservation;

    public function annuler(Reservation $reservation): Reservation;

}