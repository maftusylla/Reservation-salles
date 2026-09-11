<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\EmailDejaUtiliseException;
use App\Exception\IdentifiantsInvalidesException;
use App\Service\AuthService;
use App\View\ViewFormatterInterface;

final class AuthController extends AbstractController
{
    public function __construct(
        private readonly AuthService $auth,
        ViewFormatterInterface $formatter,
    ) {
        parent::__construct($formatter);
    }

    public function formulaireInscription(): string
    {
        return $this->page('Inscription', 'auth/inscription', ['errors' => [], 'old' => []]);
    }

    public function inscrire(): string
    {
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        if (strlen($motDePasse) < 8) {
            return $this->echecValidation(
                ['mot_de_passe' => 'Le mot de passe doit contenir au moins 8 caractères.'],
                'Inscription',
                'auth/inscription',
                ['old' => ['nom' => $nom, 'email' => $email]]
            );
        }

        try {
            $utilisateur = $this->auth->inscrire($nom, $email, $motDePasse);
        } catch (EmailDejaUtiliseException $exception) {
            return $this->echecValidation(
                ['email' => $exception->getMessage()],
                'Inscription',
                'auth/inscription',
                ['old' => ['nom' => $nom, 'email' => $email]]
            );
        }

        return $this->succes('/connexion', $utilisateur->toArray(), 201);
    }

    public function formulaireConnexion(): string
    {
        return $this->page('Connexion', 'auth/connexion', ['errors' => [], 'old' => []]);
    }

    public function connecter(): string
    {
        $email = $_POST['email'] ?? '';
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        try {
            $utilisateur = $this->auth->connecter($email, $motDePasse);
        } catch (IdentifiantsInvalidesException $exception) {
            return $this->echecValidation(
                ['general' => $exception->getMessage()],
                'Connexion',
                'auth/connexion',
                ['old' => ['email' => $email]]
            );
        }

        return $this->succes('/', $utilisateur->toArray());
    }

    public function deconnecter(): string
    {
        $this->auth->deconnecter();

        return $this->succes('/connexion');
    }
}