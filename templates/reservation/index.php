<h2>Liste des réservations</h2>

<form method="get" action="/reservations">
    <label>Filtrer par salle
        <select name="salle_id" onchange="this.form.submit()">
            <option value="">Toutes les salles</option>
            <?php foreach (($salles ?? []) as $salle): ?>
                <option value="<?= $salle->id ?>" <?= (($salleId ?? null) == $salle->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($salle->nom) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</form>

<a href="/reservations/create">Créer une réservation</a>

<table>
    <thead>
        <tr><th>Salle</th><th>Responsable</th><th>Début</th><th>Fin</th><th>Statut</th><th></th></tr>
    </thead>
    <tbody>
        <?php foreach (($reservations ?? []) as $reservation): ?>
        <tr>
            <td><?= htmlspecialchars($reservation->salle->nom ?? '—') ?></td>
            <td><?= htmlspecialchars($reservation->responsable) ?></td>
            <td><?= htmlspecialchars($reservation->date_debut->format('d/m/Y H:i')) ?></td>
            <td><?= htmlspecialchars($reservation->date_fin->format('d/m/Y H:i')) ?></td>
            <td><?= htmlspecialchars($reservation->statut) ?></td>
            <td><a href="/reservations/<?= $reservation->id ?>">Voir</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>