<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\AuthService;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly AuthService $auth,
    ) {
    }

    public function handle(callable $next): string
    {
        if ($this->auth->utilisateurConnecte() === null) {
            header('Location: /connexion');

            return '';
        }

        return $next();
    }
}