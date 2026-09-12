<?php


namespace App\Repository;

use App\Model\Salle;

final class EloquentSalleRepository implements SalleRepositoryInterface
{
    public function lister(): array
    {
        return Salle::all()->all();
    }
        public function listerPagine(int $page, int $parPage = 10): array
    {
        $paginateur = Salle::query()
            ->orderBy('nom')
            ->paginate($parPage, ['*'], 'page', $page);

        return [
            'items'         => $paginateur->items(),
            'page_actuelle' => $paginateur->currentPage(),
            'derniere_page' => $paginateur->lastPage(),
            'total'         => $paginateur->total(),
        ];
    }
    public function trouver(int $id): ?Salle
    {
        return Salle::find($id);
    }

    public function enregistrer(Salle $salle): Salle
    {
        $salle->save();

        return $salle;
    }
}