<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

       // Creation de 50 utilisateurs
       for ($i = 0; $i < 50; $i++) {
        $user = new User();
        $user->setPassword($this->passwordHasher->hashPassword($user, 'secret'));
        $user->setEmail($faker->unique()->email);
        $user->setNom($faker->name);
        $user->setIsVerified($faker->boolean);

        // Persiste les donnees
        $manager->persist($user);

        // Enregistre l'objet $user dans une reference avec un nom unique
        $this->addReference("user-$i", $user);
       }

       // Creation d'un administrateur de test
       $admin = new User();
       $admin->setPassword($this->passwordHasher->hashPassword($user, 'secret'));
       $admin->setNom('Jerome Doe');
       $admin->setRoles(['ROLE_ADMIN']);
       $admin->setEmail('demo@demo.com');
        $admin->setIsVerified(true);

        $manager->persist($admin);

       // Met à jour les donnees en BDD
        $manager->flush();
    }
}
