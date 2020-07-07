<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Recette;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RecetteFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        // creation of 5-6 categories 
        for($i = 1; $i <= 5; $i++)
        {
            $category = new Category;

            $category -> setTitle($faker->sentence())
                      -> setDescription($faker->paragraph());

            $manager  -> persist($category);

            // creation of 4 - 6 Articles per category 
            for($j = 1; $j <= mt_rand(4,6); $j++)
            {

                $recette = new Recette;

                $content = '<p>' . join($faker->paragraph(5), '</p><p>') . '</p>';

                $recette -> setTitle($faker->sentence())
                         -> setContent($content)
                         -> setImage($faker->imageUrl())
                         -> setCreatedAt($faker->dateTimeBetween('-6 months'))
                         -> setCategory($category);

                $manager->persist($recette);

            }

        }
     
        $manager->flush();
    }
}
