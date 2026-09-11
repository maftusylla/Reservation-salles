<?php

declare(strict_types=1);

namespace App\Middleware;

interface MiddlewareInterface
{
    /**
     * @param callable(): string $next Le reste de la chaîne (middleware suivant ou contrôleur final)
     */
    public function handle(callable $next): string;
}