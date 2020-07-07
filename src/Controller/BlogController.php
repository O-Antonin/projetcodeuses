<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(RecetteRepository $repo)
    {

        $repo = $this->getDoctrine()->getRepository(Recette::class);

        $recettes = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'recettes' => $recettes
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur le blog des Recettes'
            
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */

     public function show($id)
     {
         $repo = $this->getDoctrine()->getRepository(Recette::class);
         
         $recette = $repo->find($id);

         return $this->render('blog/show.html.twig', [
            'recette' => $recette

        ]);
     }
    /**
     * @Route("apropos", name = "about")
     */
    public function about()
    {
        
        return $this->render('blog/about.html.twig',[
            'title'=> 'Notre blog cuisine'
        ]);
    
      
    }

 
}




 