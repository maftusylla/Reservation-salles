<?php

declare(strict_types=1);

use App\Middleware\ErrorHandlerMiddleware;

return [
    'globaux' => [
        ErrorHandlerMiddleware::class,
    ],
];