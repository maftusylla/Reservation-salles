<?php

declare(strict_types=1);

namespace App\Exception;

use App\View\ViewFormatterInterface;
use Throwable;

final class ExceptionHandler
{
    public function __construct(
        private readonly ViewFormatterInterface $formatter,
    ) {
    }

    public function gerer(Throwable $exception): string
    {
        return match (true) {
            $exception instanceof ValidationEchoueeException => $this->formatter->echecValidation(
                $exception->resultat()->errors(),
                $exception->titre(),
                $exception->vue(),
                $exception->contexte()
            ),

            $exception instanceof SalleIndisponibleException => $this->formatter->echecValidation(
                ['general' => $exception->getMessage()],
                $exception->titre(),
                $exception->vue(),
                $exception->contexte()
            ),

            $exception instanceof EmailDejaUtiliseException => $this->formatter->echecValidation(
                ['email' => $exception->getMessage()],
                $exception->titre(),
                $exception->vue(),
                $exception->contexte()
            ),

            $exception instanceof IdentifiantsInvalidesException => $this->formatter->echecValidation(
                ['general' => $exception->getMessage()],
                $exception->titre(),
                $exception->vue(),
                $exception->contexte()
            ),

            $exception instanceof ReservationIntrouvableException => $this->formatter->repondre(
                'Introuvable',
                'error/404',
                [],
                $exception->code()
            ),

            default => $this->formatter->repondre('Erreur serveur', 'error/500', [], 500),
        };
    }
}
