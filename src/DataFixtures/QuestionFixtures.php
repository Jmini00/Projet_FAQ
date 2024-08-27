<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array {
        // Il faut que les fixtures "User" soient generées avant les questions
        return [
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        // Creation de 200 questions
       for ($i = 0; $i < 200; $i++) {

        // Choisit un numero entre 0 et 49 correspond au nbre d'utilisateurs
        $number = $faker->numberBetween(0, 49);
        $user = $this->getReference("user-$number");

        $question = new Question();
        $question->setUser($user);
        $question->setTitre($faker->sentence);
        $question->setContenu("{$faker->sentence} ?");
        $question->setDateCreation($faker->dateTimeBetween('-2 years', '-6 months'));

        // Persiste les donnees
        $manager->persist($question);

        $this->addReference("question-$i", $question);
       }

       // Met à jour les donnees en BDD
        $manager->flush();
    }
}
