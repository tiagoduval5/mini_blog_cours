<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user1 = $manager->getRepository(User::class)->findOneBy(['email' => 'user1@example.com']);
        $user2 = $manager->getRepository(User::class)->findOneBy(['email' => 'user2@example.com']);
        $user3 = $manager->getRepository(User::class)->findOneBy(['email' => 'user3@example.com']);

        $posts = $manager->getRepository(Post::class)->findAll();
        if (empty($posts)) {
            return;
        }

        $post1 = $posts[0] ?? null;
        $post2 = $posts[1] ?? null;
        $post3 = $posts[2] ?? null;
        $post4 = $posts[3] ?? null;
        $post5 = $posts[4] ?? null;
        $post6 = $posts[5] ?? null;

        $comments = [
            [
                'content' => 'Excellent article ! J\'ai appris beaucoup de choses sur Symfony. Merci pour cette introduction claire et bien structurée.',
                'post' => $post1,
                'author' => $user1,
                'status' => 'approved'
            ],
            [
                'content' => 'Très utile ! Je vais appliquer ces bonnes pratiques dans mon prochain projet.',
                'post' => $post1,
                'author' => $user2,
                'status' => 'approved'
            ],
            [
                'content' => 'Je ne suis pas d\'accord avec certains points. Pouvez-vous développer ?',
                'post' => $post2,
                'author' => $user3,
                'status' => 'approved'
            ],
            [
                'content' => 'Docker c\'est vraiment révolutionnaire ! Je n\'utilise plus rien d\'autre.',
                'post' => $post3,
                'author' => $user2,
                'status' => 'approved'
            ],
            [
                'content' => 'Ce tutoriel m\'a aidé à mieux comprendre les concepts avancés.',
                'post' => $post3,
                'author' => $user1,
                'status' => 'approved'
            ],
            [
                'content' => 'ES6 est vraiment une amélioration majeure. Tous les développeurs devraient le connaître.',
                'post' => $post4,
                'author' => $user3,
                'status' => 'approved'
            ],
            [
                'content' => 'Pourriez-vous ajouter un exemple avec TypeScript ?',
                'post' => $post4,
                'author' => $user2,
                'status' => 'pending'
            ],
            [
                'content' => 'Très informatif ! Ce contenu nous a vraiment aidés dans notre équipe.',
                'post' => $post5,
                'author' => $user1,
                'status' => 'approved'
            ],
            [
                'content' => 'J\'utilise CSS Grid depuis quelque temps et je confirme que c\'est excellent !',
                'post' => $post6,
                'author' => $user3,
                'status' => 'approved'
            ],
            [
                'content' => 'Merci pour cet article complet. C\'est exactement ce qu\'il me fallait.',
                'post' => $post6,
                'author' => $user2,
                'status' => 'approved'
            ],
            [
                'content' => 'Spam test',
                'post' => $post1,
                'author' => $user3,
                'status' => 'rejected'
            ],
        ];

        foreach ($comments as $commentData) {
            if (!$commentData['post']) {
                continue;
            }

            $comment = new Comment();
            $comment->setContent($commentData['content']);
            $comment->setCreatedAt(new \DateTime('-' . rand(1, 15) . ' days'));
            $comment->setStatus($commentData['status']);
            $comment->setAuthor($commentData['author']);
            $comment->setPost($commentData['post']);

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PostFixtures::class,
            UserFixtures::class,
        ];
    }
}
