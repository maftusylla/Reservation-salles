<h2>Connexion</h2>

<?php if (isset($errors['general'])): ?>
    <p class="erreur"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>

<form method="post" action="/connexion">
    <label>Email
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
    </label>

    <label>Mot de passe
        <input type="password" name="mot_de_passe">
    </label>

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="/inscription">S'inscrire</a></p>
