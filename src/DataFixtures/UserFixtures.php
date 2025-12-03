<?php

namespace App\DataFixtures;

use App\Entity\User;
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
        // Admin
        $admin = new User();
        $admin->setEmail('theojacoby@mail.be');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        $admin->setFirstName('Theo');
        $admin->setLastName('Jacoby');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsDisabled(false);
        $manager->persist($admin);
        $this->addReference('user_admin', $admin);

        // Utilisateurs normaux
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email());
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setRoles(['ROLE_USER']);
            $user->setIsDisabled(false);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}



