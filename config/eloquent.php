<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

return function (): Capsule {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $config = require __DIR__ . '/database.php';

    $capsule = new Capsule();
    $capsule->addConnection($config);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};