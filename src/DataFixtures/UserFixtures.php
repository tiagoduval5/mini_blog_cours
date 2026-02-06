<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'firstName' => 'Admin',
                'lastName' => 'User',
                'roles' => ['ROLE_ADMIN', 'ROLE_USER']
            ],
            [
                'email' => 'user1@example.com',
                'password' => 'user123',
                'firstName' => 'Jean',
                'lastName' => 'Dupont',
                'roles' => ['ROLE_USER']
            ],
            [
                'email' => 'user2@example.com',
                'password' => 'user123',
                'firstName' => 'Marie',
                'lastName' => 'Martin',
                'roles' => ['ROLE_USER']
            ],
            [
                'email' => 'user3@example.com',
                'password' => 'user123',
                'firstName' => 'Pierre',
                'lastName' => 'Bernard',
                'roles' => ['ROLE_USER']
            ]
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setRoles($userData['roles']);
            
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference('user-' . strtolower(str_replace('@', '-', $userData['email'])), $user);
        }

        $manager->flush();
    }
}
