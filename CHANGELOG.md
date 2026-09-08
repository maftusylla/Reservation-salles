# Changelog

Toutes les modifications notables de ce projet sont documentées ici.

## [1.0.0] - Finalisation

### Ajouté
- `ARCHITECTURE.md` : analyse des design patterns et principes SOLID appliqués (MVC, Front Controller, Router, Validator/Strategy, DTO/Builder, ORM/Active Record, Repository, Service, injection par constructeur, conteneur/autowiring/IoC)
- Section "Questions du sujet" dans `README.md` : réponses aux questions posées à chaque étape
- Diagramme de classes

### Modifié
- `README.md` complété : prérequis, installation, configuration base, migrations, seeders, lancement serveur, exécution des tests, commandes personnalisées (`fatou`)

## [0.12.0] - Tests

### Ajouté
- `InMemorySalleRepository` et `InMemoryReservationRepository` : doublures en mémoire des interfaces de Repository, pour tester les services sans MySQL
- Tests unitaires de `CreerReservationService` : réservation valide, salle inexistante, salle inactive, fin avant début, durée > 4h, date passée, conflit de créneau, réservations voisines sans chevauchement
- Tests unitaires de `SalleValidator` et `ReservationValidator` : email invalide, responsable vide, capacité négative, type inconnu, date incorrecte
- Tests d'intégration avec la vraie base : création d'une salle, relation salle/réservations, annulation d'une réservation
- `tests/bootstrap.php` : connexion SQLite en mémoire permettant à Eloquent de fonctionner dans les tests unitaires sans base réelle
- `phpunit.xml` : configuration des suites Unit/Integration
- Dépendance de développement `phpunit/phpunit`

## [0.11.0] - Configuration du conteneur d'injection

### Ajouté
- `config/eloquent.php` : bootstrap Eloquent centralisé (connexion, `setAsGlobal`, `bootEloquent`), réutilisé par le conteneur et les scripts CLI
- `config/container.php` : définitions PHP-DI (autowiring pour les repositories Eloquent, définitions explicites pour les interfaces, factories pour `Capsule\Manager` et `Renderer`)
- Injection du conteneur (`ContainerInterface`) dans `Application`, seul point de récupération directe d'objets depuis le conteneur
- Outil CLI personnalisé `fatou` avec les commandes `db:migrate` et `db:seed`

### Modifié
- `public/index.php` simplifié : construction du conteneur puis délégation à `Application::run()`
- `Application::run()` : résolution des contrôleurs via le conteneur au lieu d'un `new` manuel
- `database/migrate.php` et `database/seed.php` : réutilisent `config/eloquent.php` au lieu de dupliquer le bootstrap Eloquent

### Refactorisé
- Validation déplacée des contrôleurs vers les DTO : `CreerSalleDTO`/`CreerReservationDTO` construits via `CreerSalleDTOBuilder`/`CreerReservationDTOBuilder` (pattern Builder), qui déclenchent la validation dans `build()` et lèvent `ValidationEchoueeException` en cas d'échec (écart assumé par rapport à la séparation Validator/DTO suggérée par le sujet — voir `ARCHITECTURE.md`)
- Migrations restructurées : une classe par table (`database/migrations/001_creer_table_salles.php`, `002_creer_table_reservations.php`), implémentant `App\Migration\MigrationInterface` (`up`/`down`), orchestrées par `database/migrate.php` (tri par préfixe numérique)
- Seeders restructurés sur le même principe : `database/seeders/001_seeder_salles.php` implémentant `App\Seeder\SeederInterface`, orchestré par `database/seed.php`

## [0.10.0] - Configuration du routeur

### Ajouté
- `routes/web.php` : déclaration de toutes les routes (salles et réservations)
- Dispatch FastRoute dans `Application::run()`, avec gestion de `FOUND`, `NOT_FOUND` (404) et `METHOD_NOT_ALLOWED` (405 + en-tête `Allow`)

## [0.9.0] - Interface web

### Ajouté
- `App\View\Renderer` : moteur de rendu minimal des templates
- `SalleController` et `ReservationController` : actions `index`, `show`, `create`, `store`, `edit`, `update`, `cancel`
- Templates : `layout/base`, `salle/index|show|form`, `reservation/index|show|form`, `error/404|405`
- `public/assets/style.css` : mise en forme minimale

## [0.8.0] - Règles métier

### Ajouté
- `CreerReservationService` : validation des 9 règles métier (salle active, dates cohérentes, durée max 4h, date future, absence de chevauchement)
- `AnnulerReservationService`
- Exceptions `SalleIndisponibleException` et `ReservationIntrouvableException`

## [0.7.0] - Accès aux données

### Ajouté
- Interfaces `SalleRepositoryInterface` et `ReservationRepositoryInterface`
- Implémentations `EloquentSalleRepository` et `EloquentReservationRepository`
- Recherche de conflit de créneaux sur les réservations confirmées

## [0.6.0] - Objets de transport

### Ajouté
- `CreerSalleDTO` et `CreerReservationDTO` : données typées, construites depuis un tableau validé

## [0.5.0] - Validation

### Ajouté
- `ValidatorInterface` et `ValidationResult`
- `SalleValidator` et `ReservationValidator` (Respect\Validation)

## [0.4.0] - Données initiales

### Ajouté
- `database/seed.php` : insertion des 5 salles imposées, rejouable sans doublon (`firstOrCreate`)

## [0.3.0] - Modèles

### Ajouté
- `App\Model\Salle` et `App\Model\Reservation`, avec relation `hasMany`/`belongsTo`
- Propriétés assignables (`$fillable`) et conversions de types (`$casts`)

## [0.2.0] - Configuration d'Eloquent

### Ajouté
- `.env.example` et chargement des variables d'environnement (phpdotenv)
- `config/database.php`
- Bootstrap Eloquent initial et création des tables `salles`/`reservations`

## [0.1.0] - Initialisation du projet Composer

### Ajouté
- `composer.json` avec autoloading PSR-4 (`App\` → `src/`)
- Dépendances installées : nikic/fast-route, respect/validation, illuminate/database, php-di/php-di, vlucas/phpdotenv
- `src/Application.php` : classe d'entrée minimale

## [0.0.0] - Initialisation du dépôt

### Ajouté
- Initialisation du dépôt Git (branche `main`)
- Fichier `.gitignore`
- Squelette de `README.md`