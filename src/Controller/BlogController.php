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
    public function index(RecetteRepository $repo) // Defining a Method to display the list of all Recipes
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
    
    public function form(Recette $recette = null, Request $request, EntityManagerInterface $manager) // Defining a Method to add or create a new recipe in the DB.
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
     * @Route("/blog/category", name="list_category")
     */
    public function listCategory()
    {
        return $this->render('blog/category.html.twig');
    }
      
    /**
     * @Route("blog/contact", name="blog_contact")
     */

    public function contact(Request $request, EntityManagerInterface $manager, ContactNotification $notification) 

    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
         
 
        if ($form->isSubmitted() && $form->isValid()) 
        {

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

    public function show(Recette $recette, Request $request, EntityManagerInterface $manager) // Defining a method to display the details of a Recipe
    {
         //$repo = $this->getDoctrine()->getRepository(Recette::class);
         
         //$recette = $repo->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()) 
        {
            $comment->setCreatedAt(new \DateTime()) // on génère la date pour l'insertion
                    ->setRecette($recette); // on relie la recette au commentaire
            $manager->persist($comment); // on prépare l'insertion 
            $manager->flush();
            return $this->redirectToRoute('blog_show', [ 'id' => $recette->getId()
            ]); 
        }

         return $this->render('blog/show.html.twig', [
            'recette' => $recette,
            'commentForm' => $form->createView()
        ]);
    }

    

    /**
     * @Route("blog/{category}/categorie", name="blog_category")
     */
    public function eachCategory(CategoryRepository $repo, $category)
    {
        // $repo = $this->getDoctrine()->getRepository(Recette::class);
       //https://github.com/O-Antonin/projetcodeuses/pull/25/conflict?name=templates%252Fbase.html.twig&ancestor_oid=e6483a2ef6d1e6bec8bbd1c420e838145442d8c9&base_oid=528318ff02cc0090370f179d1f55c133d9a17ec6&head_oid=8bff39e64486b68f051f0ca14a4c31e2cc541177
        $categories = $repo->findOneBy([
            'title' => $category
        ]);

        $recettes = $categories->getRecettes();

        dump($recettes);

        return $this->render('blog/categoryList.html.twig', [
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




 