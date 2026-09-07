

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
- `database/migrations/migration.php` : connexion via `Capsule\Manager` et création des tables `salles`/`reservations`

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