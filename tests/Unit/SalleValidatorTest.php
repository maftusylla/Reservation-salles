<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

final class SalleValidatorTest extends TestCase
{
    private SalleValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new SalleValidator();
    }

    private function donneesValides(): array
    {
        return ['nom' => 'B12', 'batiment' => 'B', 'capacite' => 40, 'type' => 'cours', 'active' => true];
    }

    public function testCapaciteNegative(): void
    {
        $resultat = $this->validator->validate(array_merge($this->donneesValides(), ['capacite' => -5]));

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('capacite', $resultat->errors());
    }

    public function testTypeInconnu(): void
    {
        $resultat = $this->validator->validate(array_merge($this->donneesValides(), ['type' => 'garage']));

        $this->assertFalse($resultat->isValid());
        $this->assertArrayHasKey('type', $resultat->errors());
    }
}