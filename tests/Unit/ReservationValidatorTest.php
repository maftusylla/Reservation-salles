<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validation\ReservationValidator;
use PHPUnit\Framework\TestCase;

final class ReservationValidatorTest extends TestCase
{
    private ReservationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ReservationValidator();
    }

    private function donneesValides(): array
    {
        return [
            'salle_id'    => 1,
            'responsable' => 'Awa Ndiaye',
            'email'       => 'awa@universite.sn',
            'motif'       => "Cours d'architecture",
            'date_debut'  => '2026-09-10 10:00:00',
            'date_fin'    => '2026-09-10 12:00:00',
        ];
    }

    public function testEmailInvalide(): void
    {
        $resultat = $this->validator->validate(array_merge($this->donneesValides(), ['email' => 'pas-un-email']));

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('email', $resultat->errors());
    }

    public function testResponsableVide(): void
    {
        $resultat = $this->validator->validate(array_merge($this->donneesValides(), ['responsable' => '']));

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('responsable', $resultat->errors());
    }

    public function testDateIncorrecte(): void
    {
        $resultat = $this->validator->validate(array_merge($this->donneesValides(), ['date_debut' => 'pas-une-date']));

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('date_debut', $resultat->errors());
    }
}