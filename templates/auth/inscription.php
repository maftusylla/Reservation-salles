<h2>Créer un compte</h2>

<form method="post" action="/inscription">
    <label>Nom
        <input type="text" name="nom" value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
    </label>

    <label>Email
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    </label>
    <?php if (isset($errors['email'])): ?><p class="erreur"><?= htmlspecialchars($errors['email']) ?></p><?php endif; ?>

    <label>Mot de passe
        <input type="password" name="mot_de_passe">
    </label>
    <?php if (isset($errors['mot_de_passe'])): ?><p class="erreur"><?= htmlspecialchars($errors['mot_de_passe']) ?></p><?php endif; ?>

    <button type="submit">S'inscrire</button>
</form>

<p>Déjà un compte ? <a href="/connexion">Se connecter</a></p>