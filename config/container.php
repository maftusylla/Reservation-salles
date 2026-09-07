<?php

declare(strict_types=1);

use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\View\Renderer;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\factory;

return [
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),

    Capsule::class => factory(function (): Capsule {
        $demarrerEloquent = require __DIR__ . '/eloquent.php';

        return $demarrerEloquent();
    }),

    Renderer::class => factory(function (): Renderer {
        return new Renderer(dirname(__DIR__) . '/templates');
    }),
];