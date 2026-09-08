# Analyse architecturale

## MVC
- **Model** : `App\Model\Salle`, `App\Model\Reservation` (Eloquent)
- **View** : `templates/` + `App\View\Renderer`
- **Controller** : `App\Controller\SalleController`, `ReservationController`
Avantage : sépare données, affichage et logique de requête.
Limite : peut devenir rigide si la vue a besoin de logique complexe.

## Front Controller
- `public/index.php` : point d'entrée HTTP unique.
Avantage : centralise bootstrap, sécurité, routage.
Limite : un seul point de défaillance si mal protégé.

## Router
- `routes/web.php` + FastRoute dans `Application::run()`.
Avantage : associe URL/méthode à un handler sans réécrire un dispatcher.
Limite : dépendance à une bibliothèque externe.

## Validator (Strategy)
- `ValidatorInterface`, `SalleValidator`, `ReservationValidator`.
Avantage : stratégies interchangeables, testables isolément.
Limite : ne couvre que la validation syntaxique, pas métier.

## DTO + Builder
- `CreerSalleDTO`/`CreerReservationDTO`, construits via `CreerSalleDTOBuilder`/`CreerReservationDTOBuilder`.
Avantage : construction progressive et lisible, validation centralisée au moment du `build()`.
Limite : écart avec la suggestion initiale du sujet (validation séparée du DTO) — choisi ici pour réduire le nombre d'objets manipulés par le contrôleur.
Exemple :
```php
CreerSalleDTO::builder()->avecNom($nom)->avecCapacite($cap)->build();
```

## ORM / Active Record
- Eloquent via `Capsule\Manager`, modèles `Salle`/`Reservation` en Active Record.
Avantage : requêtes lisibles, relations déclaratives.
Limite : couplage fort au modèle de données relationnel.

## Repository
- `SalleRepositoryInterface`/`EloquentSalleRepository`, idem Reservation.
Avantage : inversion de dépendance, remplaçable par une doublure en test.
Limite : couche supplémentaire pour un ORM déjà abstrait.

## Service
- `CreerReservationService`, `AnnulerReservationService`.
Avantage : règles métier isolées, testables sans HTTP ni base.
Limite : peut grossir si trop de règles s'accumulent (à découper si besoin).

## Injection par constructeur / Conteneur / Autowiring / IoC
- `config/container.php` (PHP-DI), classes recevant leurs dépendances par constructeur.
Avantage : découplage, testabilité, un seul endroit de configuration.
Limite : complexité additionnelle pour un projet aussi petit.
Exemple :
```php
Capsule::class => factory(fn() => (require __DIR__.'/eloquent.php')()),
```

## Principes SOLID
- **S** : chaque classe a un rôle (Validator ≠ DTO ≠ Repository ≠ Service).
- **O** : ajouter une migration/seeder = nouveau fichier, sans modifier l'orchestrateur.
- **L** : `EloquentSalleRepository` substituable par `InMemorySalleRepository` sans casser le service.
- **I** : interfaces spécifiques (`SalleRepositoryInterface` ≠ `ReservationRepositoryInterface`).
- **D** : Services/Contrôleurs dépendent d'interfaces, pas d'implémentations concrètes.