# EEMI Blog 

## Architecture

### Structure Modulaire

Le projet est organisé en modules indépendants suivant une architecture hexagonale (Ports & Adapters) :

```
src/Modules/
├── Article/        # Gestion des articles
├── Comment/        # Système de commentaires
├── User/           # Gestion des utilisateurs
├── Authentication/ # Login/Logout/Registration
└── Shared/         # Code partagé (base controllers, enums, fixtures)
```

### Layers (Couches)

Chaque module suit une architecture en 4 couches :

```
Module/
├── Domain/              # Logique métier pure (entities, value objects, interfaces)
│   ├── Entities/        # Entités métier immuables
│   ├── ValueObjects/    # Objets valeur avec validation
│   ├── Repositories/    # Interfaces (ports)
│   └── Exceptions/      # Exceptions métier
│
├── Application/         # Orchestration et cas d'usage
│   ├── Commands/        # DTOs pour les opérations d'écriture
│   ├── Services/        # Facades pour simplifier l'accès
│   └── UseCases/        # Business logic (Commands & Queries)
│
├── Infrastructure/      # Implémentations techniques (adapters)
│   ├── Doctrine/        # ORM et repositories
│   └── Security/        # Voters pour l'autorisation
│
└── Presentation/        # Couche HTTP
    ├── Controllers/     # Gestion des requêtes HTTP
    ├── Forms/          # Formulaires Symfony
    ├── ViewModels/     # Modèles pour la lecture
    └── WriteModel/     # DTOs pour les formulaires
```

## Concepts Clés

### 1. Value Objects

Tous les IDs et données importantes sont encapsulés dans des Value Objects immuables avec validation :

```php
final readonly class ArticleId implements \Stringable
{
    private function __construct(private string $value) {
        Assert::notEmpty($value);
        Assert::uuid($value);
    }

    public static function fromString(string $id): self {
        return new self($id);
    }
}
```

### 2. Domain Entities vs Infrastructure Entities

- **Domain Entities** (`ArticleEntity`) : Immuables, logique métier pure
- **Doctrine Entities** (`DoctrineArticleEntity`) : Mutables, mappées en base de données

Conversion via `toDomain()` et `fromDomain()`.

### 3. CQRS Pattern

Séparation claire entre les opérations :
- **Commands** : Create, Update, Delete (UseCases/Commands/)
- **Queries** : Read operations (UseCases/Queries/)

### 4. Repository Pattern

```php
interface IArticleRepository {
    public function create(ArticleEntity $article): ArticleEntity;
    public function findById(ArticleId $id): ArticleEntity;
}

class DoctrineArticleRepository implements IArticleRepository {
    // Implementation with Doctrine ORM
}
```

### 5. Soft Delete

Toutes les entités utilisent le soft delete via le champ `deletedAt` :
- Les données ne sont jamais physiquement supprimées
- Les queries filtrent automatiquement `deletedAt IS NULL`

### 6. Security Voters

Autorisation fine-grained via des Voters :

```php
class ArticleVoter extends Voter {
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        // EDIT/DELETE: Author ou Admin uniquement
        $isAuthor = $article->getAuthorUsername() === $user->getUsername();
        $isAdmin = $user->getRole() === Roles::ROLE_ADMIN;
        return $isAuthor || $isAdmin;
    }
}
```

## Fonctionnalités

### Articles
- CRUD complet avec upload d'images
- Soft delete
- Autorisation (edit/delete par auteur ou admin)
- View models optimisés pour l'affichage

### Commentaires
- Création sur les articles
- Édition/suppression par auteur ou admin
- Soft delete
- Affichage en temps réel

### Authentification & Autorisation
- Login/Logout avec Symfony Security
- Rôles : `ROLE_USER`, `ROLE_MODERATOR`, `ROLE_ADMIN`
- Access Control pour routes protégées
- Hash de mots de passe avec Argon2

### Routes Protégées
```yaml
access_control:
    - { path: ^/articles/new, roles: ROLE_USER }
    - { path: ^/comments/article/.*/new, roles: ROLE_USER }
```

## Fixtures

Les fixtures génèrent des données de test :

### Utilisateurs (5)
| Email | Password | Role |
|-------|----------|------|
| admin@example.com | admin123 | ROLE_ADMIN |
| moderator@example.com | moderator123 | ROLE_MODERATOR |
| john.doe@example.com | user123 | ROLE_USER |
| jane.doe@example.com | user123 | ROLE_USER |
| bob.smith@example.com | user123 | ROLE_USER |

### Articles (10)
- Sujets techniques (Symfony, DDD, Architecture, PHP, Doctrine, etc.)
- Dates réparties sur les 30 derniers jours
- Auteurs aléatoires

### Commentaires (20-50)
- 2-5 commentaires par article
- Dates après la création de l'article
- Contenu contextuel basé sur le titre

## Base de Données

### Schema Principal

```sql
-- Users
CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY,
    username VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    age INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

-- Articles
CREATE TABLE articles (
    id VARCHAR(36) PRIMARY KEY,
    author_id VARCHAR(36) NOT NULL,
    heading VARCHAR(255) NOT NULL,
    subheading VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    cover_image VARCHAR(255),
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Comments
CREATE TABLE comments (
    id VARCHAR(36) PRIMARY KEY,
    article_id VARCHAR(36) NOT NULL,
    author_id VARCHAR(36) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);
```