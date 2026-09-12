<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator;

return function (): Capsule {
    $config = require __DIR__ . '/database.php';

    $capsule = new Capsule();
    $capsule->addConnection($config);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    Paginator::currentPageResolver(function (string $nomParametre = 'page'): int {
        $page = $_GET[$nomParametre] ?? 1;

        return filter_var($page, FILTER_VALIDATE_INT) !== false && $page > 0
            ? (int) $page
            : 1;
    });

    Paginator::currentPathResolver(function (): string {
        $scheme = ($_SERVER['HTTPS'] ?? 'off') === 'on' ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $chemin = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

        return $scheme . '://' . $host . $chemin;
    });

    return $capsule;
};