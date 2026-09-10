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
}