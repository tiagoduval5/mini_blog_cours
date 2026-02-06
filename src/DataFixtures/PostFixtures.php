<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $admin = $manager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $user1 = $manager->getRepository(User::class)->findOneBy(['email' => 'user1@example.com']);
        $user2 = $manager->getRepository(User::class)->findOneBy(['email' => 'user2@example.com']);
        $user3 = $manager->getRepository(User::class)->findOneBy(['email' => 'user3@example.com']);

        $catPhpSymfony = $manager->getRepository(Category::class)->findOneBy(['name' => 'PHP & Symfony']);
        $catWeb = $manager->getRepository(Category::class)->findOneBy(['name' => 'Développement Web']);
        $catTech = $manager->getRepository(Category::class)->findOneBy(['name' => 'Technologie']);
        $catPratiques = $manager->getRepository(Category::class)->findOneBy(['name' => 'Bonnes Pratiques']);

        $posts = [
            [
                'title' => 'Introduction à Symfony 7',
                'content' => 'Découvrez les bases de Symfony 7, le framework PHP le plus puissant du marché. Dans cet article, nous explorerons les concepts fondamentaux et les nouvelles fonctionnalités qui font de Symfony un choix excellent pour les développeurs professionnels.

Symfony offre une structure robuste, une documentation complète, et une communauté très active. C\'est le framework idéal pour construire des applications web scalables et maintenables.',
                'picture' => 'https://symfony.com/images/opengraph/symfony-v7-dark.png',
                'category' => $catPhpSymfony,
                'author' => $admin
            ],
            [
                'title' => 'Les meilleures pratiques en développement web',
                'content' => 'Le développement web moderne nécessite de suivre certaines bonnes pratiques pour assurer la qualité, la sécurité et la maintenabilité du code.

Parmi ces pratiques, on retrouve :
- La séparation des responsabilités
- Le respect des patterns de conception
- Une documentation claire
- Des tests automatisés
- La gestion des versions avec Git

Respecter ces pratiques dès le début de votre projet vous permettra de gagner du temps et d\'éviter des problèmes à long terme.',
                'picture' => 'https://via.placeholder.com/600x400?text=Best+Practices',
                'category' => $catPratiques,
                'author' => $user1
            ],
            [
                'title' => 'Docker pour les développeurs',
                'content' => 'Docker a révolutionné la façon dont nous développons, testons et déployons les applications. Avec Docker, vous pouvez créer des environnements reproductibles et isolés.

Les avantages de Docker :
- Cohérence entre développement et production
- Isolation des dépendances
- Facilité de collaboration
- Déploiement simplifié

Dans cet article, nous verrons comment utiliser Docker dans votre workflow de développement.',
                'picture' => 'https://via.placeholder.com/600x400?text=Docker',
                'category' => $catTech,
                'author' => $user2
            ],
            [
                'title' => 'JavaScript moderne : ES6 et au-delà',
                'content' => 'JavaScript a beaucoup évolué ces dernières années. ES6 (ECMAScript 2015) a apporté des changements majeurs et depuis, de nouvelles fonctionnalités arrivent chaque année.

Les fonctionnalités clés à connaître :
- Arrow functions
- Classes
- Destructuring
- Promises et async/await
- Modules

Maîtriser ces concepts modernes est essentiel pour tout développeur JavaScript contemporain.',
                'picture' => 'https://via.placeholder.com/600x400?text=JavaScript',
                'category' => $catWeb,
                'author' => $user1
            ],
            [
                'title' => 'REST API : Conception et bonnes pratiques',
                'content' => 'Concevoir une bonne API REST n\'est pas trivial. Il faut suivre certaines conventions et principes pour créer une API facile à utiliser et à maintenir.

Les principes fondamentaux :
- Utiliser les bons verbes HTTP
- Structurer les routes de manière logique
- Implémenter la pagination
- Gérer les erreurs correctement
- Documenter complètement

Une bonne API REST est la clé pour créer des applications scalables et flexibles.',
                'picture' => 'https://via.placeholder.com/600x400?text=REST+API',
                'category' => $catWeb,
                'author' => $admin
            ],
            [
                'title' => 'Le CSS Grid : révolutionner vos mises en page',
                'content' => 'CSS Grid est l\'une des technologies les plus puissantes du web moderne. Elle permet de créer des mises en page complexes avec facilité.

Contrairement à Flexbox qui fonctionne en une dimension, Grid travaille en deux dimensions, ce qui offre bien plus de contrôle et de flexibilité.

Avec CSS Grid, vous pouvez :
- Créer des mises en page complexes
- Aligner les éléments précisément
- Créer des designs responsifs facilement
- Réduire le besoin de media queries

Grid est maintenant supportée par tous les navigateurs modernes et c\'est le moment idéal pour l\'adopter.',
                'picture' => 'https://via.placeholder.com/600x400?text=CSS+Grid',
                'category' => $catWeb,
                'author' => $user3
            ]
        ];

        foreach ($posts as $postData) {
            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setContent($postData['content']);
            $post->setPicture($postData['picture']);
            $post->setPublishedAt(new \DateTime('-' . rand(1, 30) . ' days'));
            $post->setAuthor($postData['author']);
            $post->setCategory($postData['category']);

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
