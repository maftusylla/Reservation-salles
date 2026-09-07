<?php


namespace App\Controller;

use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\Renderer;

final class SalleController
{
    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        private readonly SalleValidator $validator,
        private readonly Renderer $renderer,
    ) {
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

        return $this->page('Détail de la salle', 'salle/show', ['salle' => $salle]);
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
        $resultat = $this->validator->validate($data);

        if (! $resultat->isValid()) {
            return $this->page('Ajouter une salle', 'salle/form', [
                'salle'  => null,
                'errors' => $resultat->errors(),
                'old'    => $data,
            ]);
        }

        $salle = new Salle($resultat->data());
        $this->salles->enregistrer($salle);

        header('Location: /salles/' . $salle->id);
        exit;
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
        $resultat = $this->validator->validate($data);

        if (! $resultat->isValid()) {
            return $this->page('Modifier une salle', 'salle/form', [
                'salle'  => $salle,
                'errors' => $resultat->errors(),
                'old'    => $data,
            ]);
        }

        $salle->fill($resultat->data());
        $this->salles->enregistrer($salle);

        header('Location: /salles/' . $salle->id);
        exit;
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

    private function page(string $titre, string $vue, array $data = [], int $code = 200): string
    {
        http_response_code($code);
        $contenu = $this->renderer->render($vue, $data);

        return $this->renderer->render('layout/base', ['titre' => $titre, 'contenu' => $contenu]);
    }
}