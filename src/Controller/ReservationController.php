<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Exception\ValidationEchoueeException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\View\ViewFormatterInterface;

final class ReservationController extends AbstractController
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly SalleRepositoryInterface $salles,
        private readonly CreerReservationService $creerReservation,
        private readonly AnnulerReservationService $annulerReservation,
        ViewFormatterInterface $formatter,
        private readonly ReservationValidator $reservation_validator,
    ) {
        parent::__construct($formatter);
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

        try {
            $dto = CreerReservationDTO::builder($this->reservation_validator)
                ->avecSalleId($data['salle_id'])
                ->avecResponsable($data['responsable'])
                ->avecEmail($data['email'])
                ->avecMotif($data['motif'])
                ->avecDateDebut($data['date_debut'])
                ->avecDateFin($data['date_fin'])
                ->build();
        } catch (ValidationEchoueeException $exception) {
            return $this->echecValidation(
                $exception->resultat()->errors(),
                'Créer une réservation',
                'reservation/form',
                ['salles' => $this->salles->lister(), 'old' => $data]
            );
        }

        try {
            $reservation = $this->creerReservation->executer($dto);
        } catch (SalleIndisponibleException $exception) {
            return $this->echecValidation(
                ['general' => $exception->getMessage()],
                'Créer une réservation',
                'reservation/form',
                ['salles' => $this->salles->lister(), 'old' => $data]
            );
        }

        return $this->succes('/reservations/' . $reservation->id, $reservation->toArray(), 201);
    }

    public function cancel(int $id): string
    {
        try {
            $reservation = $this->annulerReservation->executer($id);
        } catch (ReservationIntrouvableException) {
            return $this->page('Réservation introuvable', 'error/404', [], 404);
        }

        return $this->succes('/reservations/' . $id, $reservation->toArray());
    }
}