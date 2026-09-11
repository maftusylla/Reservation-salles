<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Utilisateur;

interface UtilisateurRepositoryInterface
{
    public function trouverParEmail(string $email): ?Utilisateur;

    public function trouver(int $id): ?Utilisateur;

    public function enregistrer(Utilisateur $utilisateur): Utilisateur;
}