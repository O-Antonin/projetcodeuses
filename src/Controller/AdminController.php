<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
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
     * @Route("/admin/{id}/edit-recette", name="admin_edit_recette")
     */
    public function editrecettes(recette $recette)
    {
        dump($recette);
        //$form = $this->createForm(RecetteType::class, $recette);
        return $this->render('admin/edit_recette.html.twig'//,[
        //    'formEdit' => $form->createView()
        //]);
        );
    }





}


