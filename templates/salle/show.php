<?php if (!isset($salle)): ?>
    <p>Salle introuvable.</p>
<?php else: ?>
<h2><?= htmlspecialchars($salle->nom) ?></h2>
<ul>
    <li>Bâtiment : <?= htmlspecialchars($salle->batiment) ?></li>
    <li>Capacité : <?= htmlspecialchars((string) $salle->capacite) ?></li>
    <li>Type : <?= htmlspecialchars($salle->type) ?></li>
    <li>Statut : <?= $salle->active ? 'Active' : 'Inactive' ?></li>
</ul>
<a href="/salles/<?= $salle->id ?>/edit">Modifier</a>
<a href="/reservations?salle_id=<?= $salle->id ?>">Voir ses réservations</a>
<?php endif; ?>