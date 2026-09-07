<?php

declare(strict_types=1);

namespace App;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;

use function FastRoute\simpleDispatcher;

final class Application
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    public function run(): void
    {
        $this->container->get(\Illuminate\Database\Capsule\Manager::class);

        $routes = require dirname(__DIR__) . '/routes/web.php';

        $dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes): void {
            $routes($r);
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo $this->page404();
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                header('Allow: ' . implode(', ', $routeInfo[1]));
                echo $this->page405();
                break;

            case Dispatcher::FOUND:
                [$controllerClass, $methode] = $routeInfo[1];
                $parametres = array_map('intval', $routeInfo[2]);

                $controleur = $this->container->get($controllerClass);

                echo $controleur->$methode(...$parametres);
                break;
        }
    }

    private function page404(): string
    {
        $renderer = $this->container->get(\App\View\Renderer::class);

        return $renderer->render('layout/base', [
            'titre'   => 'Page introuvable',
            'contenu' => $renderer->render('error/404'),
        ]);
    }

    private function page405(): string
    {
        $renderer = $this->container->get(\App\View\Renderer::class);

        return $renderer->render('layout/base', [
            'titre'   => 'Méthode non autorisée',
            'contenu' => $renderer->render('error/405'),
        ]);
    }
}