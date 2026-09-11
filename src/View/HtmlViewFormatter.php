<?php

declare(strict_types=1);

namespace App\View;

final class HtmlViewFormatter implements ViewFormatterInterface
{
    public function __construct(
        private readonly Renderer $renderer,
    ) {
    }

    public function repondre(string $titre, string $vue, array $data = [], int $code = 200): string
    {
        http_response_code($code);
        $contenu = $this->renderer->render($vue, $data);

        return $this->renderer->render('layout/base', ['titre' => $titre, 'contenu' => $contenu]);
    }

    public function succes(string $url, array $donnees = [], int $code = 200): string
    {
        header('Location: ' . $url);
        exit;
    }

    public function echecValidation(array $errors, string $titre, string $vue, array $contexte = [], int $code = 422): string
    {
        return $this->repondre($titre, $vue, array_merge($contexte, ['errors' => $errors]));
    }
}
