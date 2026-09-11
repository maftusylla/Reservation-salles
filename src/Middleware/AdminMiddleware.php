<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\AuthService;

final class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly AuthService $auth,
    ) {
    }

    public function handle(callable $next): string
    {
        $utilisateur = $this->auth->utilisateurConnecte();

        if ($utilisateur === null) {
            header('Location: /connexion');

            return '';
        }

        if ($utilisateur->role !== 'admin') {
            http_response_code(403);

            return '403 — Accès réservé aux administrateurs.';
        }

        return $next();
    }
}