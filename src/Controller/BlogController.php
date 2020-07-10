<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Recette;
use App\Entity\Category;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
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
      * @Route("blog/contact", name="blog_contact")
      */

      public function contact(Request $request, EntityManagerInterface $manager, ContactNotification $notification) 

      {
         $contact = new Contact();

         $form = $this->createForm(ContactType::class, $contact);

         $form->handleRequest($request);
         
 
         if ($form->isSubmitted() && $form->isValid()) {

            $notification->notify($contact);

            $manager->persist($contact);

            $manager->flush();

            $this->addFlash('success', 'Votre Email a bien été envoyé');   

            return $this->redirectToRoute('blog_contact');
 
         }
 
         return $this->render("blog/contact.html.twig", [
             'formContact'=> $form->createView()
         ]);
 
      }

    /**
     * @Route("/blog/{id}", name="blog_show")
    */

     public function show(Recette $recette, Request $request, EntityManagerInterface $manager)
     {
         //$repo = $this->getDoctrine()->getRepository(Recette::class);
         
         //$recette = $repo->find($id);

         $comment = new Comment();
         $form = $this->createForm(CommentType::class, $comment);
         $form->handleRequest($request); 

         if($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime()) // on génère la date pour l'insertion
                    ->setRecette($recette); // on relie la recette au commentaire
            $manager->persist($comment); // on prépare l'insertion 
            $manager->flush();
            return $this->redirectToRoute('blog_show', [ 'id' => $recette->getId()
            ]); }
         
         


         return $this->render('blog/show.html.twig', [
            'recette' => $recette,
            'commentForm' => $form->createView()

            

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
    /** 
     * @Route("/apropos", name="about")
     */
    public function about()
    {
        
        return $this->render('blog/about.html.twig',[
            'title'=> 'Notre blog cuisine'
        ]);


    
      
    }

     

 
}




 