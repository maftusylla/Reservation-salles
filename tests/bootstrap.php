<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();
$capsule->addConnection([
    'driver'   => 'sqlite',
    'database' => ':memory:',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();