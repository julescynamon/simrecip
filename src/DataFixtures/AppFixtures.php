<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recette;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    private Generator $faker;


    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }



    public function load(ObjectManager $manager): void
    {
        // ingredient
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++) {

            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100));

            $ingredients[] = $ingredient;

            $manager->persist($ingredient);
        }

        // recettes
        for ($j = 0; $j < 25; $j++) {
            $recette = new Recette();
            $recette->setName($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(0, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(0, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(0, 5) : null)
                ->setDescription($this->faker->paragraph(3))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(0, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);

            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recette->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recette);
        }

        // Users
        for ($l = 0; $l < 10; $l++) {
            $user = new User();
            $user->setEmail($this->faker->email())
                ->setFullName($this->faker->name())
                ->setPseudo($this->faker->userName())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $manager->persist($user);
        }

        $manager->flush();
    }
}
