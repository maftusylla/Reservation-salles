<?php


namespace App;

use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use App\View\Renderer;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

final class Application
{
    public function run(): void
    {
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
                $parametres = $routeInfo[2];

                $controleur = $this->construireControleur($controllerClass);

                echo $controleur->$methode(...array_map('intval', $parametres));
                break;
        }
    }

    private function construireControleur(string $controllerClass): SalleController|ReservationController
    {
        $renderer = new Renderer(dirname(__DIR__) . '/templates');
        $salles = new EloquentSalleRepository();
        $reservations = new EloquentReservationRepository();

        return match ($controllerClass) {
            SalleController::class => new SalleController(
                $salles,
                new SalleValidator(),
                $renderer,
            ),
            ReservationController::class => new ReservationController(
                $reservations,
                $salles,
                new ReservationValidator(),
                new CreerReservationService($salles, $reservations),
                new AnnulerReservationService($reservations),
                $renderer,
            ),
        };
    }

    private function page404(): string
    {
        $renderer = new Renderer(dirname(__DIR__) . '/templates');

        return $renderer->render('layout/base', [
            'titre'    => 'Page introuvable',
            'contenu'  => $renderer->render('error/404'),
        ]);
    }

    private function page405(): string
    {
        $renderer = new Renderer(dirname(__DIR__) . '/templates');

        return $renderer->render('layout/base', [
            'titre'    => 'Méthode non autorisée',
            'contenu'  => $renderer->render('error/405'),
        ]);
    }
}
