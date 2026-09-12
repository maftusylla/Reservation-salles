<?php


namespace App\Repository;

use App\Model\Salle;

interface SalleRepositoryInterface
{
    /**
     * @return Salle[]
     */
    public function lister(): array;
        public function listerPagine(int $page, int $parPage = 10): array;

    public function trouver(int $id): ?Salle;

    public function enregistrer(Salle $salle): Salle;
}