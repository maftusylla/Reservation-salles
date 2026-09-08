<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Service\CreerReservationService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CreerReservationServiceTest extends TestCase
{
    private InMemorySalleRepository $salles;
    private InMemoryReservationRepository $reservations;
    private CreerReservationService $service;

    protected function setUp(): void
    {
        $this->salles = new InMemorySalleRepository();
        $this->reservations = new InMemoryReservationRepository();
        $this->service = new CreerReservationService($this->salles, $this->reservations);

        $salle = new Salle(['nom' => 'B12', 'batiment' => 'B', 'capacite' => 40, 'type' => 'cours', 'active' => true]);
        $salle->id = 1;
        $this->salles->ajouter($salle);
    }

    private function dto(array $overrides = []): CreerReservationDTO
    {
        $defaults = [
            'salleId'    => 1,
            'responsable' => 'Awa Ndiaye',
            'email'      => 'awa@universite.sn',
            'motif'      => "Cours d'architecture logicielle",
            'dateDebut'  => new DateTimeImmutable('+1 day 10:00'),
            'dateFin'    => new DateTimeImmutable('+1 day 12:00'),
        ];
        $data = array_merge($defaults, $overrides);

        return new CreerReservationDTO(...$data);
    }

    public function testReservationValide(): void
    {
        $reservation = $this->service->executer($this->dto());

        $this->assertSame('confirmée', $reservation->statut);
    }

    public function testSalleInexistante(): void
    {
        $this->expectException(SalleIndisponibleException::class);
        $this->service->executer($this->dto(['salleId' => 999]));
    }

    public function testSalleInactive(): void
    {
        $salleInactive = new Salle(['nom' => 'Labo', 'batiment' => 'C', 'capacite' => 24, 'type' => 'laboratoire', 'active' => false]);
        $salleInactive->id = 2;
        $this->salles->ajouter($salleInactive);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->executer($this->dto(['salleId' => 2]));
    }

    public function testFinAvantDebut(): void
    {
        $this->expectException(SalleIndisponibleException::class);
        $this->service->executer($this->dto([
            'dateDebut' => new DateTimeImmutable('+1 day 12:00'),
            'dateFin'   => new DateTimeImmutable('+1 day 10:00'),
        ]));
    }

    public function testDureeSuperieureAQuatreHeures(): void
    {
        $this->expectException(SalleIndisponibleException::class);
        $this->service->executer($this->dto([
            'dateDebut' => new DateTimeImmutable('+1 day 08:00'),
            'dateFin'   => new DateTimeImmutable('+1 day 14:00'),
        ]));
    }

    public function testDatePassee(): void
    {
        $this->expectException(SalleIndisponibleException::class);
        $this->service->executer($this->dto([
            'dateDebut' => new DateTimeImmutable('-1 day 10:00'),
            'dateFin'   => new DateTimeImmutable('-1 day 12:00'),
        ]));
    }

    public function testConflitAvecReservationExistante(): void
    {
        $this->service->executer($this->dto([
            'dateDebut' => new DateTimeImmutable('+1 day 10:00'),
            'dateFin'   => new DateTimeImmutable('+1 day 12:00'),
        ]));

        $this->expectException(SalleIndisponibleException::class);
        $this->service->executer($this->dto([
            'dateDebut' => new DateTimeImmutable('+1 day 11:00'),
            'dateFin'   => new DateTimeImmutable('+1 day 13:00'),
        ]));
    }

    public function testReservationVoisineSansChevauchement(): void
    {
        $this->service->executer($this->dto([
            'dateDebut' => new DateTimeImmutable('+1 day 10:00'),
            'dateFin'   => new DateTimeImmutable('+1 day 12:00'),
        ]));

        $reservation = $this->service->executer($this->dto([
            'dateDebut' => new DateTimeImmutable('+1 day 12:00'),
            'dateFin'   => new DateTimeImmutable('+1 day 14:00'),
        ]));

        $this->assertSame('confirmée', $reservation->statut);
    }
}