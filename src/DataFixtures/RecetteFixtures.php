<?php

namespace App\DataFixtures;


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
      
        $manager->flush();
    }
}
