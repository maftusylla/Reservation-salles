<?php
declare(strict_types=1);
namespace App\DTO;


use App\Exception\ValidationEchoueeException;
use App\Validation\SalleValidator;


final class CreerSalleDTOBuilder
{
    private array $data = [];
    private string $titre = 'Formulaire invalide';
    private string $vue = 'salle/form';
    private array $contexte = [];
    
    public function __construct(
        private readonly SalleValidator $validator
    ) {
    }
    public function avecContexte(string $titre, string $vue, array $contexte = []): self
    {
        $this->titre = $titre;
        $this->vue = $vue;
        $this->contexte = $contexte;

        return $this;
    }

    public function avecNom(string $nom): self
    {
        $this->data['nom'] = $nom;

        return $this;
    }

    public function avecBatiment(string $batiment): self
    {
        $this->data['batiment'] = $batiment;

        return $this;
    }

    public function avecCapacite(mixed $capacite): self
    {
        $this->data['capacite'] = $capacite;

        return $this;
    }

    public function avecType(string $type): self
    {
        $this->data['type'] = $type;

        return $this;
    }

    public function avecActive(mixed $active): self
    {
        $this->data['active'] = $active;

        return $this;
    }

    
    public function build(): CreerSalleDTO
    {
        $resultat = $this->validator->validate($this->data);

        if (! $resultat->isValid()) {
            throw new ValidationEchoueeException(
                $resultat,
                $this->titre,
                $this->vue,
                array_merge($this->contexte, ['old' => $this->data])
            );
        }

        return CreerSalleDTO::depuisTableau($resultat->data());
    }
}
