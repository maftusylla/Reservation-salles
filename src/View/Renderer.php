<?php


namespace App\View;

final class Renderer
{
    public function __construct(
        private readonly string $templatesPath,
    ) {
    }

    public function render(string $template, array $data = []): string
    {
        extract($data);

        ob_start();
        require $this->templatesPath . '/' . $template . '.php';

        return (string) ob_get_clean();
    }
}