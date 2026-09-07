<h2><?= !isset($salle) || $salle === null ? 'Ajouter une salle' : 'Modifier la salle' ?></h2>

<form method="post" action="<?= !isset($salle) || $salle === null ? '/salles' : '/salles/' . $salle->id . '/edit' ?>">
    <label>Nom
        <input type="text" name="nom" value="<?= htmlspecialchars($old['nom'] ?? $salle->nom ?? '') ?>">
    </label>
    <?php if (isset($errors['nom'])): ?><p class="erreur"><?= htmlspecialchars($errors['nom']) ?></p><?php endif; ?>

    <label>Bâtiment
        <input type="text" name="batiment" value="<?= htmlspecialchars($old['batiment'] ?? $salle->batiment ?? '') ?>">
    </label>
    <?php if (isset($errors['batiment'])): ?><p class="erreur"><?= htmlspecialchars($errors['batiment']) ?></p><?php endif; ?>

    <label>Capacité
        <input type="number" name="capacite" value="<?= htmlspecialchars((string) ($old['capacite'] ?? $salle->capacite ?? '')) ?>">
    </label>
    <?php if (isset($errors['capacite'])): ?><p class="erreur"><?= htmlspecialchars($errors['capacite']) ?></p><?php endif; ?>

    <label>Type
        <select name="type">
            <?php foreach (['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'] as $type): ?>
                <option value="<?= $type ?>" <?= ($old['type'] ?? $salle->type ?? '') === $type ? 'selected' : '' ?>>
                    <?= htmlspecialchars($type) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (isset($errors['type'])): ?><p class="erreur"><?= htmlspecialchars($errors['type']) ?></p><?php endif; ?>

    <label>
        <input type="checkbox" name="active" <?= ($old['active'] ?? $salle->active ?? true) ? 'checked' : '' ?>>
        Active
    </label>

    <button type="submit">Enregistrer</button>
</form>