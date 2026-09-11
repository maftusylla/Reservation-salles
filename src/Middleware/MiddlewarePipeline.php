<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

final class MiddlewarePipeline
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    /**
     * @param array<class-string<MiddlewareInterface>> $middlewareClasses
     * @param callable(): string $destination Le contrôleur final, appelé si toute la chaîne laisse passer
     */
    public function traiter(array $middlewareClasses, callable $destination): string
    {
        $pipeline = array_reduce(
            array_reverse($middlewareClasses),
            function (callable $next, string $middlewareClass): callable {
                return function () use ($middlewareClass, $next): string {
                    $middleware = $this->container->get($middlewareClass);

                    return $middleware->handle($next);
                };
            },
            $destination
        );

        return $pipeline();
    }
}