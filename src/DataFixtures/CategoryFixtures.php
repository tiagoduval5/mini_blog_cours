<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            [
                'name' => 'Technologie',
                'description' => 'Articles sur les dernières technologies, frameworks et outils de développement.'
            ],
            [
                'name' => 'Développement Web',
                'description' => 'Tutoriels et guides pour le développement web front-end et back-end.'
            ],
            [
                'name' => 'PHP & Symfony',
                'description' => 'Tout ce qui concerne PHP, Symfony et l\'écosystème associé.'
            ],
            [
                'name' => 'Actualités',
                'description' => 'Nouvelles et mises à jour du monde de la programmation.'
            ],
            [
                'name' => 'Tutoriels',
                'description' => 'Guides pas à pas pour apprendre de nouvelles compétences.'
            ],
            [
                'name' => 'Bonnes Pratiques',
                'description' => 'Conseils et recommandations pour écrire du code de qualité.'
            ]
        ];

        foreach ($categories as $catData) {
            $category = new Category();
            $category->setName($catData['name']);
            $category->setDescription($catData['description']);
            $manager->persist($category);
            $this->addReference('category-' . strtolower(str_replace(' ', '-', $catData['name'])), $category);
        }

        $manager->flush();
    }
}
