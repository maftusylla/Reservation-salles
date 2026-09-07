<h2>Liste des salles</h2>
<a href="/salles/create">Ajouter une salle</a>
<table>
    <thead>
        <tr><th>Nom</th><th>Bâtiment</th><th>Capacité</th><th>Type</th><th>Active</th><th></th></tr>
    </thead>
    <tbody>
        <?php foreach ($salles ?? [] as $salle): ?>
        <tr>
            <td><?= htmlspecialchars($salle->nom) ?></td>
            <td><?= htmlspecialchars($salle->batiment) ?></td>
            <td><?= htmlspecialchars((string) $salle->capacite) ?></td>
            <td><?= htmlspecialchars($salle->type) ?></td>
            <td><?= $salle->active ? 'Oui' : 'Non' ?></td>
            <td>
                <a href="/salles/<?= $salle->id ?>">Voir</a>
                <a href="/salles/<?= $salle->id ?>/edit">Modifier</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>