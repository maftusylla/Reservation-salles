<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Exception\ValidationEchoueeException;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\ViewFormatterInterface;

final class SalleController extends AbstractController
{
    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        ViewFormatterInterface $formatter,
        private readonly SalleValidator $salle_validator,
        private readonly \App\Service\AuthService $auth,
    ) {
        parent::__construct($formatter);
    }

    public function index(): string
    {
        return $this->page('Liste des salles', 'salle/index', [
            'salles' => $this->salles->lister(),
        ]);
    }

   public function show(int $id): string
{
    $salle = $this->salles->trouver($id);

    if ($salle === null) {
        return $this->page('Salle introuvable', 'error/404', [], 404);
    }

    return $this->page('Détail de la salle', 'salle/show', [
        'salle' => $salle,
        'utilisateurConnecte' => $this->auth->utilisateurConnecte(),
    ]);
    }

    public function create(): string
    {
        return $this->page('Ajouter une salle', 'salle/form', [
            'salle'  => null,
            'errors' => [],
            'old'    => [],
        ]);
    }

    public function store(): string
    {
        $data = $this->donneesFormulaire();

        try {
            $dto = CreerSalleDTO::builder($this->salle_validator)
                ->avecNom($data['nom'])
                ->avecBatiment($data['batiment'])
                ->avecCapacite($data['capacite'])
                ->avecType($data['type'])
                ->avecActive($data['active'])
                ->build();
        } catch (ValidationEchoueeException $exception) {
            return $this->echecValidation(
                $exception->resultat()->errors(),
                'Ajouter une salle',
                'salle/form',
                ['salle' => null, 'old' => $data]
            );
        }

        $salle = new Salle([
            'nom'      => $dto->nom,
            'batiment' => $dto->batiment,
            'capacite' => $dto->capacite,
            'type'     => $dto->type,
            'active'   => $dto->active,
        ]);

        $this->salles->enregistrer($salle);

        return $this->succes('/salles/' . $salle->id, $salle->toArray(), 201);
    }

    public function edit(int $id): string
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->page('Salle introuvable', 'error/404', [], 404);
        }

        return $this->page('Modifier une salle', 'salle/form', [
            'salle'  => $salle,
            'errors' => [],
            'old'    => [],
        ]);
    }

    public function update(int $id): string
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->page('Salle introuvable', 'error/404', [], 404);
        }

        $data = $this->donneesFormulaire();

        try {
            $dto = CreerSalleDTO::builder($this->salle_validator)
                ->avecNom($data['nom'])
                ->avecBatiment($data['batiment'])
                ->avecCapacite($data['capacite'])
                ->avecType($data['type'])
                ->avecActive($data['active'])
                ->build();
        } catch (ValidationEchoueeException $exception) {
            return $this->echecValidation(
                $exception->resultat()->errors(),
                'Modifier une salle',
                'salle/form',
                ['salle' => $salle, 'old' => $data]
            );
        }

        $salle->fill([
            'nom'      => $dto->nom,
            'batiment' => $dto->batiment,
            'capacite' => $dto->capacite,
            'type'     => $dto->type,
            'active'   => $dto->active,
        ]);

        $this->salles->enregistrer($salle);

        return $this->succes('/salles/' . $salle->id, $salle->toArray());
    }

    public function toggleActive(int $id): string
    {
        $salle = $this->salles->trouver($id);

        if ($salle === null) {
            return $this->page('Salle introuvable', 'error/404', [], 404);
        }

        $salle->active = ! $salle->active;
        $this->salles->enregistrer($salle);

        return $this->succes('/salles/' . $salle->id, $salle->toArray());
    }

    private function donneesFormulaire(): array
    {
        return [
            'nom'      => $_POST['nom'] ?? '',
            'batiment' => $_POST['batiment'] ?? '',
            'capacite' => $_POST['capacite'] ?? '',
            'type'     => $_POST['type'] ?? '',
            'active'   => isset($_POST['active']),
        ];
    }
}