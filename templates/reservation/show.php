<?php if (!isset($reservation)): ?>
    <p>Réservation introuvable.</p>
<?php else: ?>
    <h2>Réservation #<?= $reservation->id ?></h2>
    <ul>
        <li>Salle : <?= htmlspecialchars($reservation->salle->nom ?? '—') ?></li>
        <li>Responsable : <?= htmlspecialchars($reservation->responsable) ?></li>
        <li>Email : <?= htmlspecialchars($reservation->email) ?></li>
        <li>Motif : <?= htmlspecialchars($reservation->motif) ?></li>
        <li>Début : <?= htmlspecialchars($reservation->date_debut->format('d/m/Y H:i')) ?></li>
        <li>Fin : <?= htmlspecialchars($reservation->date_fin->format('d/m/Y H:i')) ?></li>
        <li>Statut : <?= htmlspecialchars($reservation->statut) ?></li>
    </ul>

    <?php if ($reservation->statut === 'confirmée'): ?>
        <form method="post" action="/reservations/<?= $reservation->id ?>/cancel">
            <button type="submit">Annuler la réservation</button>
        </form>
    <?php endif; ?>
<?php endif; ?>