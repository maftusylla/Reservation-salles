<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Utilisateur;

final class EloquentUtilisateurRepository implements UtilisateurRepositoryInterface
{
    public function trouverParEmail(string $email): ?Utilisateur
    {
        return Utilisateur::where('email', $email)->first();
    }

    public function trouver(int $id): ?Utilisateur
    {
        return Utilisateur::find($id);
    }

    public function enregistrer(Utilisateur $utilisateur): Utilisateur
    {
        $utilisateur->save();

        return $utilisateur;
    }
}