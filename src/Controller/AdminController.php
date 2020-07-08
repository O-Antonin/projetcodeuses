<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Comment;
use App\Entity\Recette;
use App\Entity\Category;
use App\Form\RecetteType;
use App\Form\CategoryType;
use App\Form\RegistrationType;
use App\Repository\UsersRepository;
use App\Repository\CommentRepository;
use App\Repository\RecetteRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    
    /**
     * @Route("/admin/recettes", name="admin_recettes")
     */
    public function adminRecettes(RecetteRepository $repo)
    {   
       
        $em = $this->getDoctrine()->getManager();

       
        $colonnes = $em->getClassMetadata(Recette::class)->getFieldNames();

        $recettes = $repo->findAll();

        dump($recettes);
        dump($colonnes);
        
        return $this->render('admin/admin_recettes.html.twig', [
            'recettes' => $recettes,
            'colonnes' =>$colonnes
        ]);
    }



    /**
     * @Route("/admin/recettes/new", name="admin_new_recette")      
     * @Route("/admin/{id}/edit-recette", name="admin_edit_recette")      
     */    
    public function editRecettes(Recette $recette = null, Request $request, EntityManagerInterface $manager)     
    {         
        dump($recette);  
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
            $this->addFlash('success', 'Les modifications ont bien été enregistrés !');  
            return $this->redirectToRoute('admin_recettes');         
        }  
        return $this->render('admin/edit_recette.html.twig', [             
            'formEdit' => $form->createView(),             
            'editMode' => $recette->getId() !== null        
             ]);     
    
    }

    /**      
     * @Route("admin/{id}/delete-recette", name="admin_delete_recette")      
     */     
    public function deleteRecette(Recette $recette, EntityManagerInterface $manager)     
    {         
        $manager->remove($recette);         
        $manager->flush();  
        $this->addFlash('success', "L'recette a bien été supprimé !");  
        return $this->redirectToRoute('admin_recettes');     
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function adminUsers(UsersRepository $repo)
    {   
       
        $em = $this->getDoctrine()->getManager();

       
        $colonnes = $em->getClassMetadata(Users::class)->getFieldNames();

        $users = $repo->findAll();

        dump($users);
        dump($colonnes);
        
        return $this->render('admin/admin_users.html.twig', [
            'users' => $users,
            'colonnes' =>$colonnes
        ]);
    }

    /**
     * @Route("/admin/{id}/delete-user", name="admin_delete_user")
     */
    public function deleteUser(Users $users, EntityManagerInterface $manager)
    {
        $manager->remove($users);
        $manager->flush();
        $this->addFlash('sucees',"L'membre a bien été supprimé !");
        return $this->redirectToRoute('admin_users');
    }
   

    /**
     * @Route("/admin/comments", name="admin_comments")
     */
    public function adminComments(CommentRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();
        $colonnes = $em->getClassMetadata(Comment::class)->getFieldNames();

        $comments= $repo->findAll();


        dump($colonnes);
        dump($comments);

        return $this->render('admin/admin_comments.html.twig', [
            'comments' => $comments,
            'colonnes' =>$colonnes

        ]);

    }

    /**
     * @Route("/admin/{id}/delete-comment", name="admin_delete_comment")
     */
    public function deleteComment(Comment $comments, EntityManagerInterface $manager)
    {
        $manager->remove($comments);
        $manager->flush();
        $this->addFlash('sucees', "L'membre a bien été supprimé !");
        return $this->redirectToRoute('admin_comments');
    }


    /**
     * @Route("/admin/categorys", name="admin_categorys")
     */
    public function adminCategorys(CategoryRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();

        $colonnes = $em->getClassMetadata(Category::class)->getFieldNames();

        $categorys = $repo->findAll();

        dump($categorys);
        dump($colonnes);

        return $this->render('admin/admin_categorys.html.twig', [
            'categorys' => $categorys,
            'colonnes' => $colonnes
        ]);

    }

    /**
     * @Route("/admin/categorys/new", name="admin_new_category")
     * @Route("/admin/{id}/edit-category", name="admin_edit_category")
     */
    public function editCategorys(Category $category = null, Request $request, EntityManagerInterface $manager)     
    {         
        dump($category);  
        if(!$category)         
        {             
            $category = new category;         
        }  
        $form = $this->createForm(CategoryType::class, $category);  
        $form->handleRequest($request);  
        if($form->isSubmitted() && $form->isValid())          
        {                
         
            $manager->persist($category);               
            $manager->flush();   
            $this->addFlash('success', 'Les modifications ont bien été enregistrés !');  
            return $this->redirectToRoute('admin_categorys');         
        }  
        return $this->render('admin/edit_category.html.twig', [             
            'formEdit' => $form->createView(),             
            'editMode' => $category->getId() !== null        
             ]);     
    
    }


    /**      
     * @Route("admin/{id}/delete-category", name="admin_delete_category")      
     */     
    public function deleteCategory(Category $category, EntityManagerInterface $manager)
    {
        dump($category->getRecettes());

        if($category->getRecettes()->isEmpty())
        {
            $manager->remove($category);
            $manager->flush();

            $this->addFlash('success', "La catégorie a bien été supprimé !");

            return $this->redirectToRoute('admin_categorys');
        }
        else
        {
            $this->addFlash('danger', "Des articles sont encore associé à la catégorie, il est donc impossible de la supprimer !");

            return $this->redirectToRoute('admin_categorys');
        }
    }


    

}    


