<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\ExceptionHandler;
use Throwable;

final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ExceptionHandler $exceptionHandler,
    ) {
    }

    public function handle(callable $next): string
    {
        try {
            return $next();
        } catch (Throwable $exception) {
            return $this->exceptionHandler->gerer($exception);
        }
    }
}
