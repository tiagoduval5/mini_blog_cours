# Blog Symfony 7

Un blog moderne et fonctionnel construit avec Symfony 7, Bootstrap 5 et SQLite.

## 🚀 Fonctionnalités

- **Authentification** - Inscription et connexion sécurisées avec validation email
- **Articles** - Créer, lire, modifier et supprimer des articles
- **Catégories** - Organiser les articles par catégorie
- **Commentaires** - Système de commentaires avec modération par admin
- **Panel Admin** - Gestion complète des articles, catégories, commentaires et utilisateurs
- **Rôles** - Distinction entre utilisateurs normaux et administrateurs
- **Responsive** - Design adapté mobile et desktop
- **Français** - Interface entièrement en français avec fuseau horaire Europe/Paris

## 📋 Prérequis

- PHP 8.3+
- Composer
- Symfony CLI (optionnel mais recommandé)

## 🔧 Installation

```bash
# Cloner le projet
git clone <ton-repo>
cd td_symfony

# Installer les dépendances
composer install

# Configurer la base de données
symfony console doctrine:migrations:migrate

# Charger les données de test
symfony console doctrine:fixtures:load

# Lancer le serveur
symfony serve
```

Accède à l'application sur `http://localhost:8000`

## 👥 Utilisateurs de test

Après avoir chargé les fixtures:

| Email | Mot de passe | Rôle |
|-------|------------|------|
| admin@example.com | admin123 | Admin |
| user1@example.com | user123 | Utilisateur |

## 📁 Structure du projet

```
src/
├── Controller/     # Contrôleurs (Admin, Auth, Post, Profile, etc.)
├── Entity/         # Entités (User, Post, Category, Comment)
├── Form/           # Types de formulaires
├── Repository/     # Repositories Doctrine
└── EventListener/  # Écouteurs d'événements

templates/
├── admin/          # Templates panel admin
├── auth/           # Pages d'authentification
├── post/           # Pages articles
├── profile/        # Profil utilisateur
└── partials/       # Composants réutilisables

assets/
├── styles/         # Feuilles de style personnalisées
└── controllers/    # Contrôleurs Stimulus JS
```

## �️ Routes principales

### Authentification
- `GET /` - Accueil
- `GET /login` - Page de connexion
- `POST /login` - Traitement connexion
- `GET /register` - Inscription
- `POST /register` - Traitement inscription
- `GET /logout` - Déconnexion

### Articles
- `GET /articles` - Liste des articles
- `GET /article/{id}` - Détail d'un article
- `GET /article/new` - Créer un article
- `POST /article` - Sauvegarder nouvel article
- `GET /article/{id}/edit` - Éditer un article
- `POST /article/{id}/edit` - Sauvegarder modifications
- `POST /article/{id}/delete` - Supprimer un article

### Catégories
- `GET /categories` - Liste des catégories
- `GET /category/{id}` - Articles d'une catégorie
- `GET /category/new` - Créer une catégorie (admin)
- `POST /category` - Sauvegarder catégorie
- `GET /category/{id}/edit` - Éditer une catégorie (admin)
- `POST /category/{id}/edit` - Sauvegarder modifications
- `POST /category/{id}/delete` - Supprimer une catégorie (admin)

### Commentaires
- `POST /article/{id}/comment` - Ajouter un commentaire

### Profil
- `GET /profile` - Voir le profil
- `GET /profile/edit` - Éditer le profil
- `POST /profile/edit` - Sauvegarder profil
- `GET /profile/posts` - Mes articles
- `GET /profile/comments` - Mes commentaires

### Admin (ROLE_ADMIN requis)
- `GET /admin` - Tableau de bord
- `GET /admin/posts` - Gestion des articles
- `POST /admin/posts/{id}/delete` - Supprimer un article
- `GET /admin/categories` - Gestion des catégories
- `POST /admin/categories/{id}/delete` - Supprimer une catégorie
- `GET /admin/comments` - Modération des commentaires
- `POST /admin/comments/{id}/approve` - Approuver un commentaire
- `POST /admin/comments/{id}/reject` - Rejeter un commentaire
- `POST /admin/comments/{id}/delete` - Supprimer un commentaire
- `GET /admin/users` - Gestion des utilisateurs
- `POST /admin/users/{id}/toggle` - Activer/désactiver un utilisateur
- `POST /admin/users/{id}/role` - Promouvoir/retirer admin

## �🔐 Sécurité

- Mots de passe hashés en bcrypt
- Protection CSRF sur tous les formulaires
- Validation email lors de l'inscription
- Gestion des rôles et permissions
- Pages d'erreur personnalisées (403, etc.)

## 📝 API

L'application inclut une API REST avec documentation Swagger accessible sur `/api/doc`

## 🐛 Troubleshooting

**Erreur base de données ?**
```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

**Besoin de regénérer les migrations ?**
```bash
symfony console make:migration
symfony console doctrine:migrations:migrate
```

**Réinitialiser les données de test ?**
```bash
symfony console doctrine:database:drop --force
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

