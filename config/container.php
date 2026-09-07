<?php

declare(strict_types=1);

use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\View\Renderer;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\factory;

return [
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),

    Capsule::class => factory(function (): Capsule {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();

        $config = require dirname(__DIR__) . '/config/database.php';

        $capsule = new Capsule();
        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    }),

    Renderer::class => factory(function (): Renderer {
        return new Renderer(dirname(__DIR__) . '/templates');
    }),
];