<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Comment;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

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
                
                 // Création entre 4 et 10 commentaires par recette
                for($k =1; $k <= mt_rand(4,10); $k++)

                {   // On instancie l'entité comment afin d'insérer des commentaires dans la BDD
                    $comment = new Comment;

                    $content = '<p>'   .join($faker->paragraphs(2), '<p></p>') .  '<p>';
                      
                    $now = new \DateTime;
                    $interval = $now->diff($recette->getCreatedAt()); //Représente le temps en timestamps entre la date de création de l'article et maintenant
                    $days = $interval->days; //nombre de jour entre la date de création de la recette et maintenant
                    $minimum = '-' .$days . ' days';

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setRecette($recette); // On relie nos commentaires aux articles crées ci-dessus ( clé étrangère)

                    $manager->persist($comment); // On prépare l'insertion des commentaires



                }

            }
        

        }
    
        $manager->flush();
    }
}
