<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recette;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RecetteFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $faker = \Faker\Factory::create('fr_FR');

        for($j = 1; $j <= mt_rand(4,6); $j++)
            {   
                
                $recette = new Recette;

                $content = '<p>' .join($faker->paragraphs(5),'</p><p>') .'</p>'; 

                
                $recette->setTitle($faker->sentence()) //titre aléatoire
                        ->setContent($content)          //parqgraphes aléatoire        
                        ->setImage($faker->imageUrl())  //génère des URL d'image lorempixel aléatoire
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'));// création de date de commentaire d'il y a 6 mois à
                        // aujour'hui
                        //->setCategory($category); // on relie nos articles aus catégories crées juste au desus (clé étrangère)
                $manager->persist($recette);

            }    

        $manager->flush();
    }
}
