<?php


namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\View\Renderer;

final class ReservationController
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly SalleRepositoryInterface $salles,
        private readonly ReservationValidator $validator,
        private readonly CreerReservationService $creerReservation,
        private readonly AnnulerReservationService $annulerReservation,
        private readonly Renderer $renderer,
    ) {
    }

    public function index(): string
    {
        $salleId = isset($_GET['salle_id']) && $_GET['salle_id'] !== ''
            ? (int) $_GET['salle_id']
            : null;

        $reservations = $this->reservations->lister();

        if ($salleId !== null) {
            $reservations = array_values(array_filter(
                $reservations,
                static fn ($reservation) => $reservation->salle_id === $salleId
            ));
        }

        return $this->page('Liste des réservations', 'reservation/index', [
            'reservations' => $reservations,
            'salles'       => $this->salles->lister(),
            'salleId'      => $salleId,
        ]);
    }

    public function show(int $id): string
    {
        $reservation = $this->reservations->trouver($id);

        if ($reservation === null) {
            return $this->page('Réservation introuvable', 'error/404', [], 404);
        }

        return $this->page('Détail de la réservation', 'reservation/show', ['reservation' => $reservation]);
    }

    public function create(): string
    {
        return $this->page('Créer une réservation', 'reservation/form', [
            'salles' => $this->salles->lister(),
            'errors' => [],
            'old'    => [],
        ]);
    }

    public function store(): string
    {
        $data = [
            'salle_id'    => $_POST['salle_id'] ?? '',
            'responsable' => $_POST['responsable'] ?? '',
            'email'       => $_POST['email'] ?? '',
            'motif'       => $_POST['motif'] ?? '',
            'date_debut'  => $_POST['date_debut'] ?? '',
            'date_fin'    => $_POST['date_fin'] ?? '',
        ];

        $resultat = $this->validator->validate($data);

        if (! $resultat->isValid()) {
            return $this->page('Créer une réservation', 'reservation/form', [
                'salles' => $this->salles->lister(),
                'errors' => $resultat->errors(),
                'old'    => $data,
            ]);
        }

        $dto = CreerReservationDTO::depuisTableau($resultat->data());

        try {
            $reservation = $this->creerReservation->executer($dto);
        } catch (SalleIndisponibleException $exception) {
            return $this->page('Créer une réservation', 'reservation/form', [
                'salles' => $this->salles->lister(),
                'errors' => ['general' => $exception->getMessage()],
                'old'    => $data,
            ]);
        }

        header('Location: /reservations/' . $reservation->id);
        exit;
    }

    public function cancel(int $id): string
    {
        try {
            $this->annulerReservation->executer($id);
        } catch (ReservationIntrouvableException) {
            return $this->page('Réservation introuvable', 'error/404', [], 404);
        }

        header('Location: /reservations/' . $id);
        exit;
    }

    private function page(string $titre, string $vue, array $data = [], int $code = 200): string
    {
        http_response_code($code);
        $contenu = $this->renderer->render($vue, $data);

        return $this->renderer->render('layout/base', ['titre' => $titre, 'contenu' => $contenu]);
    }
}