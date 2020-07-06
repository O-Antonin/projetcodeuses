<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RecetteFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=1; $i <= 12; $i++)
        {
            $recette = new Recette(); // instantiating Class 'Recette' thats been created under Entity/Recette.php 

            $recette->setTitle("Titre de l'article n°$i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("http://placehold.it/250x150")
                    ->setCreatedAt(new \DateTime()); 

            $manager->persist($recette);

           
        }

        $manager->flush();
    }
}
