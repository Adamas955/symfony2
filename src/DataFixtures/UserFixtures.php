<?php

namespace App\DataFixtures;

use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new UserEntity();
        $user1->setUsername('esclave');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'esclave'));
        $manager->persist($user1);

        $user2 = new UserEntity();
        $user2->setUsername('esclave2');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'esclave'));
        $manager->persist($user2);

        $user3 = new UserEntity();
        $user3->setUsername('patron');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setPassword($this->passwordHasher->hashPassword($user3, 'patron'));
        $manager->persist($user3);

        $manager->flush();
    }
}
