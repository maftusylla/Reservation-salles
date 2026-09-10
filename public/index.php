<?php

declare(strict_types=1);

session_start();

use App\Application;
use Dotenv\Dotenv;
use DI\ContainerBuilder;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$builder = new ContainerBuilder();
$builder->addDefinitions(
    dirname(__DIR__) . '/config/container.php'
);
$container = $builder->build();

$application = $container->get(Application::class);
$application->run();