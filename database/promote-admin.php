<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Model\Utilisateur;

$demarrerEloquent = require dirname(__DIR__) . '/config/eloquent.php';
$demarrerEloquent();

$email = $argv[1] ?? null;

if ($email === null) {
    fwrite(STDERR, "Usage: php fatou admin:promote <email>\n");
    exit(1);
}

$utilisateur = Utilisateur::where('email', $email)->first();

if ($utilisateur === null) {
    fwrite(STDERR, "Aucun utilisateur trouvé avec l'email : {$email}\n");
    exit(1);
}

$utilisateur->role = 'admin';
$utilisateur->save();

echo "L'utilisateur {$utilisateur->nom} ({$email}) est maintenant administrateur.\n";