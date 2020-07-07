<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\UsersRepository;
use App\Repository\RecetteRepository;
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



}



