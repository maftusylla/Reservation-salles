<?php

declare(strict_types=1);

$options = [];

if (!empty($_ENV['DB_SSL_CA'])) {
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => $_ENV['DB_SSL_CA'],
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    ];
}

return [
    'driver'   => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host'     => $_ENV['DB_HOST'] ?? 'db',
    'port'     => $_ENV['DB_PORT'] ?? '3306',
    'database' => $_ENV['DB_DATABASE'] ?? 'reservation_salles',
    'username' => $_ENV['DB_USERNAME'] ?? 'reservation_user',
    'password' => $_ENV['DB_PASSWORD'] ?? 'root',
    'options'  => $options,
];