<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\ViewFormatterInterface;

abstract class AbstractController
{
    protected function __construct(
        protected readonly ViewFormatterInterface $formatter,
    ) {
    }

    protected function page(string $titre, string $vue, array $data = [], int $code = 200): string
    {
        return $this->formatter->repondre($titre, $vue, $data, $code);
    }

    protected function succes(string $url, array $donnees = [], int $code = 200): string
    {
        return $this->formatter->succes($url, $donnees, $code);
    }

    protected function echecValidation(array $errors, string $titre, string $vue, array $contexte = [], int $code = 422): string
    {
        return $this->formatter->echecValidation($errors, $titre, $vue, $contexte, $code);
    }
}