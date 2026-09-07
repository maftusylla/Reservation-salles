<?php
return [
    'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port'      => $_ENV['DB_PORT'] ?? '3306',
    'database'  => $_ENV['DB_DATABASE'] ?? 'reservation_salles',
    'username'  => $_ENV['DB_USERNAME'] ?? 'reservation_user',
    'password'  => $_ENV['DB_PASSWORD'] ?? 'root',
   
];
