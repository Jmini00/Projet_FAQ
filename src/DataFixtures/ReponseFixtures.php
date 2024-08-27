<?php

namespace App\DataFixtures;

use App\Entity\Reponse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ReponseFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array {
        // Il faut que les fixtures "User" soient generées avant les questions
        return [
            UserFixtures::class,
            QuestionFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        // Creation de 1000 reponses
       for ($i = 0; $i < 1000; $i++) {

        // Recuperation d'un utilisateur aleatoire
        $user = $this->getReference("user-{$faker->numberBetween(0, 49)}");

        // Recuperation d'une question aleatoire
        $question = $this->getReference("question-{$faker->numberBetween(0, 199)}");
        $dateCreationQuestion = $question->getDateCreation()->format('Y-m-d H:i:s');

        $reponse = new Reponse();
        $reponse->setUser($user);
        $reponse->setQuestion($question);
        $reponse->setContenu($faker->realText);
        $reponse->setDateCreation($faker->dateTimeBetween($dateCreationQuestion));

        // Ajout de votes à ma réponse
        for($j = 0; $j < $faker->numberBetween(0, 15); $j++) {
            // Recupere un utilisateur de maniere aleatoire
            $user = $this->getReference("user-{$faker->numberBetween(0, 49)}");

            // Ajoute l'utilisateur à la collection
            $reponse->addVoter($user);

        }

        // Persiste les donnees
        $manager->persist($reponse);
       }

        $manager->flush();
    }
}
