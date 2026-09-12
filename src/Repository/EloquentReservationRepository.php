<?php


namespace App\Repository;

use App\Model\Reservation;
use DateTimeImmutable;

final class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function lister(): array
    {
        return Reservation::all()->all();
    }
        public function listerPagine(?int $salleId, int $page, int $parPage = 10): array
    {
        $requete = Reservation::query();

        if ($salleId !== null) {
            $requete->where('salle_id', $salleId);
        }

        $paginateur = $requete->orderBy('date_debut', 'desc')
            ->paginate($parPage, ['*'], 'page', $page);

        return [
            'items'         => $paginateur->items(),
            'page_actuelle' => $paginateur->currentPage(),
            'derniere_page' => $paginateur->lastPage(),
            'total'         => $paginateur->total(),
        ];
    }

    public function trouver(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function rechercherConflit(
        int $salleId,
        DateTimeImmutable $dateDebut,
        DateTimeImmutable $dateFin
    ): ?Reservation {
        return Reservation::query()
            ->where('salle_id', $salleId)
            ->where('statut', 'confirmée')
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->first();
    }

    public function enregistrer(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulée';
        $reservation->save();

        return $reservation;
    }
}