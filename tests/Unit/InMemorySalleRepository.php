<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;

final class InMemorySalleRepository implements SalleRepositoryInterface
{
    private array $salles = [];

    public function ajouter(Salle $salle): void
    {
        $this->salles[$salle->id] = $salle;
    }

    public function lister(): array
    {
        return array_values($this->salles);
    }
        public function listerPagine(int $page, int $parPage = 10): array
    {
        $salles = array_values($this->salles);

        $total        = count($salles);
        $dernierePage = max(1, (int) ceil($total / $parPage));
        $items        = array_slice($salles, ($page - 1) * $parPage, $parPage);

        return [
            'items'         => $items,
            'page_actuelle' => $page,
            'derniere_page' => $dernierePage,
            'total'         => $total,
        ];
    }

    public function trouver(int $id): ?Salle
    {
        return $this->salles[$id] ?? null;
    }

    public function enregistrer(Salle $salle): Salle
    {
        $this->salles[$salle->id] = $salle;

        return $salle;
    }
}