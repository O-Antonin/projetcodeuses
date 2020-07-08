<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Category;
use App\Form\RecetteType;
use App\Repository\CategoryRepository;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("blog/new", name="blog_create")
    */
    
    public function form(Recette $recette = null, Request $request, EntityManagerInterface $manager) 
    {

        dump($request);

        if(!$recette)
        {
            $recette = new Recette;
        }
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {

            if(!$recette->getId())
            {

               $recette->setCreatedAt(new \DateTime);

            }
            $manager->persist($recette);
            $manager->flush(); 

            dump($recette);

            return $this->redirectToRoute('blog_show',[
                    'id' => $recette->getId()
                ]);
        }



        return $this->render('blog/create.html.twig', [
            'formRecette' => $form->createView(), /// Predefined Symfony Method 'createView' which follows the above method 'createFormBuilder'
            'editMode' => $recette->getId() !=null    
        ]);
        
    }

    

    /**
     * @Route("/blog/{id}", name="blog_show")
    */

     public function show(Recette $recette)
     {
         //$repo = $this->getDoctrine()->getRepository(Recette::class);
         
         //$recette = $repo->find($id);

         dump($recette);

         return $this->render('blog/show.html.twig', [
            'recette' => $recette

        ]);
     }

    /**
     * @Route("blog/{category}/categorie", name="blog_category")
     */
   
    public function oriental(CategoryRepository $repo, $category)
    {
        // $repo = $this->getDoctrine()->getRepository(Recette::class);

        $categories = $repo->findOneBy([
            'title' => $category
        ]);

        $recettes = $categories->getRecettes();

        dump($recettes);

        return $this->render('base.html.twig', [
            'controller_name' => 'BlogController',
            'category' => $categories
        ]);
    }
    
}


 