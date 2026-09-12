<?php


namespace App\Exception;

use RuntimeException;

final class SalleIndisponibleException extends RuntimeException
{
  public function __construct(
        string $message,
        private readonly string $titre = 'Réservation impossible',
        private readonly string $vue = 'reservation/form',
        private readonly array $contexte = [],
    ) {
        parent::__construct($message);
    }

    public function titre(): string
    {
        return $this->titre;
    }

    public function vue(): string
    {
        return $this->vue;
    }

    public function contexte(): array
    {
        return $this->contexte;
    }
}
