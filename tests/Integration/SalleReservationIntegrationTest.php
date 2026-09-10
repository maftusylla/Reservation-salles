<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Model\Reservation;
use App\Model\Salle;
use DateTimeImmutable;

final class SalleReservationIntegrationTest extends IntegrationTestCase
{
    public function testCreationSalleAvecEloquent(): void
    {
        $salle = Salle::create([
            'nom' => 'Test Intégration ' . uniqid(),
            'batiment' => 'Bâtiment Test',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);

        $this->assertNotNull($salle->id);

        $salle->delete();
    }

    public function testRelationSalleReservations(): void
    {
        $salle = Salle::create([
            'nom' => 'Test Relation ' . uniqid(),
            'batiment' => 'B',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id'    => $salle->id,
            'responsable' => 'Test',
            'email'       => 'test@universite.sn',
            'motif'       => 'Test relation',
            'date_debut'  => new DateTimeImmutable('+1 day 10:00'),
            'date_fin'    => new DateTimeImmutable('+1 day 12:00'),
            'statut'      => 'confirmée',
        ]);

        $this->assertCount(1, $salle->fresh()->reservations);
        $this->assertSame($salle->id, $reservation->salle->id);

        $reservation->delete();
        $salle->delete();
    }

    public function testAnnulationReservation(): void
    {
        $salle = Salle::create([
            'nom' => 'Test Annulation ' . uniqid(),
            'batiment' => 'B',
            'capacite' => 20,
            'type' => 'cours',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id'    => $salle->id,
            'responsable' => 'Test',
            'email'       => 'test@universite.sn',
            'motif'       => 'Test annulation',
            'date_debut'  => new DateTimeImmutable('+1 day 10:00'),
            'date_fin'    => new DateTimeImmutable('+1 day 12:00'),
            'statut'      => 'confirmée',
        ]);

        $reservation->statut = 'annulée';
        $reservation->save();

        $this->assertSame('annulée', $reservation->fresh()->statut);

        $reservation->delete();
        $salle->delete();
    }
}