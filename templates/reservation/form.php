<h2>Créer une réservation</h2>

<?php if (isset($errors['general'])): ?>
    <p class="erreur"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>

<form method="post" action="/reservations">
    <label>Salle
        <select name="salle_id">
            <?php foreach (($salles ?? []) as $salle): ?>
                <option value="<?= $salle->id ?>" <?= ($old['salle_id'] ?? '') == $salle->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($salle->nom) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (isset($errors['salle_id'])): ?><p class="erreur"><?= htmlspecialchars($errors['salle_id']) ?></p><?php endif; ?>

    <label>Responsable
        <input type="text" name="responsable" value="<?= htmlspecialchars($old['responsable'] ?? '') ?>">
    </label>
    <?php if (isset($errors['responsable'])): ?><p class="erreur"><?= htmlspecialchars($errors['responsable']) ?></p><?php endif; ?>

    <label>Email
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    </label>
    <?php if (isset($errors['email'])): ?><p class="erreur"><?= htmlspecialchars($errors['email']) ?></p><?php endif; ?>

    <label>Motif
        <textarea name="motif"><?= htmlspecialchars($old['motif'] ?? '') ?></textarea>
    </label>
    <?php if (isset($errors['motif'])): ?><p class="erreur"><?= htmlspecialchars($errors['motif']) ?></p><?php endif; ?>

    <label>Date de début
        <input type="datetime-local" name="date_debut" value="<?= htmlspecialchars($old['date_debut'] ?? '') ?>">
    </label>
    <?php if (isset($errors['date_debut'])): ?><p class="erreur"><?= htmlspecialchars($errors['date_debut']) ?></p><?php endif; ?>

    <label>Date de fin
        <input type="datetime-local" name="date_fin" value="<?= htmlspecialchars($old['date_fin'] ?? '') ?>">
    </label>
    <?php if (isset($errors['date_fin'])): ?><p class="erreur"><?= htmlspecialchars($errors['date_fin']) ?></p><?php endif; ?>

    <button type="submit">Réserver</button>
</form>