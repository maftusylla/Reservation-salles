<?php

declare(strict_types=1);

use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use App\View\HtmlViewFormatter;
use App\View\JsonViewFormatter;
use App\View\Renderer;
use App\View\ViewFormatterInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\factory;

$viewConfig = require __DIR__ . '/view.php';


return [
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),
    ReservationValidator::class=>autowire(),
    SalleValidator::class=>autowire(),

    Capsule::class => factory(function (): Capsule {
        $demarrerEloquent = require __DIR__ . '/eloquent.php';

        return $demarrerEloquent();
    }),

    Renderer::class => factory(function (): Renderer {
        return new Renderer(dirname(__DIR__) . '/templates');
    }),
    ViewFormatterInterface::class => autowire(
        $viewConfig['format'] === 'json' ? JsonViewFormatter::class : HtmlViewFormatter::class
    ),
    
];