## Questions du sujet

### Étape 1 — Composer

**Quel est le rôle de Composer ?**
Composer joue deux rôles : gestionnaire de dépendances (télécharger et verrouiller les versions des bibliothèques externes comme FastRoute, Eloquent, PHP-DI) et autoloader (associer automatiquement les namespaces PHP aux fichiers, sans `require` manuel).

**Quelle différence existe entre `require` et `require-dev` ?**
`require` liste les dépendances nécessaires en production (FastRoute, Eloquent, PHP-DI...). `require-dev` liste celles utiles uniquement pendant le développement (ici, PHPUnit) — elles ne sont pas installées si on déploie avec `composer install --no-dev`.

**Pourquoi faut-il versionner `composer.lock` ?**
Il fige les versions exactes installées. Sans lui, deux exécutions de `composer install` à des moments différents pourraient récupérer des versions différentes des mêmes paquets, menant à des comportements incohérents entre les machines.

**Pourquoi ne versionne-t-on pas `vendor/` ?**
Ce dossier est entièrement reconstructible à partir de `composer.json`/`composer.lock` via `composer install`. Le versionner alourdirait le dépôt sans apporter d'information utile.

### Étape 2 — Eloquent

**Quel rôle joue `Capsule\Manager` ?**
Il permet d'utiliser Eloquent en dehors du framework Laravel : il configure la connexion (`addConnection`), la rend accessible globalement (`setAsGlobal`) et démarre le moteur Active Record (`bootEloquent`).

**Pourquoi Eloquent peut-il fonctionner sans Laravel ?**
Eloquent est packagé séparément dans `illuminate/database`, sans dépendance obligatoire au reste du framework. `Capsule\Manager` remplace la configuration automatique que ferait le conteneur de service de Laravel.

**Où doit se trouver le démarrage de l'ORM ?**
Dans une factory centralisée (`config/eloquent.php`), appelée par le conteneur PHP-DI (`config/container.php`) pour les requêtes web, et réutilisée par les scripts CLI (`database/migrate.php`, `database/seed.php`) — un seul endroit connaît la logique de connexion.

**Quelle différence existe entre ORM et SQL écrit à la main ?**
L'ORM (Eloquent) manipule des objets PHP et génère le SQL automatiquement, avec relations et validations de type intégrées. Le SQL manuel offre un contrôle total mais demande d'écrire et maintenir chaque requête, avec plus de risques d'erreurs de syntaxe ou d'injection si mal géré.

### Étape 3 — Modèles

**Quel type de relation Eloquent avez-vous utilisé ?**
`hasMany` sur `Salle` (une salle a plusieurs réservations) et `belongsTo` sur `Reservation` (une réservation appartient à une salle) — relation 1-N classique.

**Pourquoi déclarer `$fillable` ?**
Pour se protéger de l'assignation de masse non contrôlée : seules les colonnes listées peuvent être remplies via `create()`/`fill()`, empêchant qu'un utilisateur malveillant injecte des colonnes non prévues (ex. `id`).

**Pourquoi convertir `active` en booléen ?**
En base, c'est stocké comme un entier (`TINYINT`). Le cast `boolean` permet de manipuler `true`/`false` côté PHP plutôt que `1`/`0`, plus lisible et moins sujet à erreur dans les comparaisons.

**Pourquoi convertir les dates en objets ?**
Le cast `datetime` transforme les colonnes `date_debut`/`date_fin` en objets manipulables (comparaisons `<`, `>`, formatage), plutôt qu'en simples chaînes de caractères — indispensable pour les règles de chevauchement du service.

### Étape 4 — Données initiales

**Quelle différence existe entre migration et seeder ?**
La migration crée/modifie la **structure** de la base (tables, colonnes). Le seeder insère des **données** dans cette structure déjà en place — deux responsabilités distinctes, exécutées dans cet ordre.

**Pourquoi les données initiales doivent-elles être reproductibles ?**
Pour pouvoir relancer le script (après un reset de base, sur une nouvelle machine, en CI) sans se soucier de l'état préalable — le script doit produire le même résultat, qu'il soit lancé une ou dix fois.

**Comment empêcher les doublons ?**
Via `Salle::firstOrCreate(['nom' => ...], $donnees)` : cherche d'abord une salle avec ce nom, n'en crée une nouvelle que si elle n'existe pas encore.

### Étape 5 — Validation

**Pourquoi séparer la validation syntaxique des règles métier ?**
La validation vérifie la **forme** des données (un email a le bon format, une capacité est un entier positif) indépendamment du contexte. Les règles métier (salle active, absence de conflit) nécessitent d'interroger la base et dépendent de l'état du système — deux logiques différentes, testables séparément.

**Pourquoi créer une interface de validation ?**
`ValidatorInterface` permet d'avoir plusieurs stratégies de validation interchangeables (`SalleValidator`, `ReservationValidator`) manipulées de façon uniforme, et facilite l'ajout d'un nouveau validateur sans toucher au code existant.

**Pourquoi le validateur ne doit-il pas enregistrer les données ?**
Il aurait alors deux responsabilités (vérifier + persister), ce qui violerait le principe de responsabilité unique et rendrait le test plus complexe (nécessiterait une base de données pour tester une simple règle de format).

**Comment retourner plusieurs erreurs en une seule fois ?**
En parcourant toutes les règles sans s'arrêter à la première erreur trouvée, et en accumulant chaque échec dans un tableau `$errors` indexé par nom de champ, retourné via `ValidationResult::errors()`.

### Étape 6 — DTO

**Quelle différence existe entre DTO et modèle Eloquent ?**
Le DTO est un simple porteur de données typées et immuables (`readonly`), sans comportement ni lien avec la base. Le modèle Eloquent représente une ligne de table, avec des méthodes de persistance (`save()`, `delete()`) et des relations.

**Pourquoi le DTO ne doit-il pas appeler `save()` ?**
Ça mélangerait transport de données et persistance — le DTO doit rester une structure passive, la sauvegarde étant la responsabilité du Repository, orchestrée par le Service.

**À quel moment transforme-t-on les chaînes en dates ?**
Dans le DTO lui-même, une fois les données validées syntaxiquement — c'est le DTO qui garantit le typage fort (`DateTimeImmutable`) pour tout le reste du flux.

**Le DTO doit-il contenir la règle de chevauchement ?**
Non — cette règle dépend de l'état de la base (autres réservations existantes), donc elle relève du Service, pas d'un simple objet de transport.

### Étape 7 — Repositories

**Eloquent constitue-t-il déjà un accès aux données ?**
Oui, Eloquent est déjà un ORM qui encapsule l'accès SQL. Mais utiliser directement `Salle::query()` dans les contrôleurs/services les coupleraient fortement à Eloquent.

**Pourquoi ajouter un Repository au-dessus d'Eloquent ?**
Pour respecter l'inversion de dépendance : les couches métier (services, contrôleurs) dépendent d'une interface (`SalleRepositoryInterface`), pas de l'implémentation concrète — ce qui permet de substituer Eloquent par une doublure en mémoire pour les tests (voir `InMemorySalleRepository`).

**Cette abstraction est-elle toujours nécessaire ?**
Pas systématiquement dans tous les projets, mais utile dès qu'on veut tester sans base de données ou garder la possibilité de changer d'ORM/de stockage sans réécrire la logique métier.

**Quel avantage apporte-t-elle ?**
Testabilité (via doublures en mémoire) et découplage — confirmé concrètement par les tests unitaires de `CreerReservationService`, qui tournent sans MySQL grâce à cette abstraction.

### Étape 8 — Services

**Pourquoi ces règles ne sont-elles pas dans le contrôleur ?**
Le contrôleur gère la couche HTTP (lire la requête, rediriger, afficher). Mélanger règles métier et logique HTTP rendrait le code difficile à tester isolément et à réutiliser (ex. depuis une commande CLI ou une API).

**Pourquoi le service dépend-il d'une interface de Repository ?**
Pour ne pas être couplé à Eloquent — le service reste utilisable et testable même sans base de données réelle, en lui fournissant une implémentation en mémoire.

**Quelle exception doit être levée en cas de conflit ?**
`SalleIndisponibleException`, utilisée pour tous les refus liés à la disponibilité de la salle (inactive, dates invalides, conflit de créneau).

**Comment tester le service sans MySQL ?**
En injectant des implémentations en mémoire des interfaces de Repository (`InMemorySalleRepository`, `InMemoryReservationRepository`) à la place des implémentations Eloquent — voir `tests/Unit/CreerReservationServiceTest.php`.

### Étape 10 — FastRoute

**Pourquoi FastRoute ne construit-il pas lui-même le contrôleur ?**
Parce qu'il ne connaît pas les dépendances nécessaires à ce contrôleur (Repository, Validator, Service...) — c'est le rôle du conteneur d'injection, qui sait comment assembler chaque objet.

**Quelle différence existe entre 404 et 405 ?**
404 signifie que l'URL demandée ne correspond à aucune route déclarée. 405 signifie que l'URL existe, mais que la méthode HTTP utilisée (ex. `DELETE`) n'est pas autorisée pour cette route — la réponse inclut alors l'en-tête `Allow` listant les méthodes acceptées.

**Pourquoi contraindre `{id}` avec `\d+` ?**
Pour que seule une chaîne entièrement numérique corresponde à ce paramètre — évite qu'une URL comme `/salles/abc` matche la route et provoque une erreur de type plus loin dans le contrôleur.

**Quel composant doit interpréter le handler retourné ?**
`Application::run()` : il reçoit le tableau `[Controller::class, 'methode']` renvoyé par FastRoute, résout le contrôleur via le conteneur, puis appelle la méthode correspondante.

### Étape 11 — PHP-DI

**Quelle différence existe entre injection et conteneur ?**
L'injection de dépendances est le principe consistant à fournir ses dépendances à un objet plutôt qu'il les construise lui-même (via constructeur ici). Le conteneur est l'outil qui automatise cette construction et cette injection à grande échelle.

**Qu'est-ce que l'autowiring ?**
La capacité de PHP-DI à construire automatiquement un objet en lisant les types déclarés dans son constructeur, sans qu'on ait besoin de l'écrire explicitement dans `container.php` (utilisé ici pour les contrôleurs, services, validateurs).

**Pourquoi les interfaces nécessitent-elles une définition explicite ?**
PHP-DI ne peut pas deviner seul quelle implémentation concrète utiliser pour une interface (`SalleRepositoryInterface` pourrait avoir plusieurs implémentations) — il faut le préciser via `autowire(EloquentSalleRepository::class)`.

**Pourquoi limiter `$container->get()` au point d'entrée ?**
Pour éviter que chaque classe ait une dépendance cachée au conteneur lui-même (anti-pattern Service Locator) — seule `Application`, le point d'entrée logique, y a recours ; toutes les autres classes reçoivent leurs dépendances par constructeur.

**Quel anti-pattern apparaît si toutes les classes interrogent le conteneur ?**
Le *Service Locator* : les dépendances d'une classe deviennent invisibles (cachées derrière `$container->get(...)`), rendant le code plus difficile à tester et à comprendre, puisqu'on ne sait plus de quoi une classe a réellement besoin sans lire tout son code.


## Utilisation avec Docker (bonus)

Ce projet peut aussi être lancé entièrement via Docker, sans installer PHP/MySQL localement.

### Prérequis
- Docker et Docker Compose

### Démarrage

```bash
cp .env.docker.example .env
docker compose build
docker compose up -d
docker compose exec app php fatou db:migrate
docker compose exec app php fatou db:seed
```

L'application est accessible sur http://localhost:8000

### Arrêt

```bash
docker compose down          # garde les données
docker compose down -v       # supprime aussi les données MySQL
```