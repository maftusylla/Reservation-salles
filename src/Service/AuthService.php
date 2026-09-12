<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\SessionManager;
use App\Exception\EmailDejaUtiliseException;
use App\Exception\IdentifiantsInvalidesException;
use App\Model\Utilisateur;
use App\Repository\UtilisateurRepositoryInterface;

final class AuthService
{
    public function __construct(
        private readonly UtilisateurRepositoryInterface $utilisateurs,
    ) {
    }
 
    public function inscrire(string $nom, string $email, string $motDePasse): Utilisateur
    {
        if ($this->utilisateurs->trouverParEmail($email) !== null) {
            throw new EmailDejaUtiliseException('Cet email est déjà utilisé.');
        }

        $utilisateur = new Utilisateur([
            'nom'          => $nom,
            'email'        => $email,
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
            'role'         => 'responsable',
        ]);

        return $this->utilisateurs->enregistrer($utilisateur);
    }

    public function connecter(string $email, string $motDePasse): Utilisateur
    {
        $utilisateur = $this->utilisateurs->trouverParEmail($email);

        if ($utilisateur === null || ! password_verify($motDePasse, $utilisateur->mot_de_passe)) {
            throw new IdentifiantsInvalidesException('Email ou mot de passe incorrect.');
        }

        SessionManager::saveData('userConnected', [
            'id'    => $utilisateur->id,
            'nom'   => $utilisateur->nom,
            'email' => $utilisateur->email,
            'role'  => $utilisateur->role,
        ]);

        return $utilisateur;
    }

    public function deconnecter(): void
    {
        SessionManager::removeData('userConnected');
    }

    public function utilisateurConnecte(): ?Utilisateur
    {
        if (! SessionManager::isConnected()) {
            return null;
        }

        $donnees = SessionManager::getData('userConnected');
        $id = $donnees['id'] ?? null;

        if ($id === null) {
            return null;
        }

        return $this->utilisateurs->trouver((int) $id);
    }
}