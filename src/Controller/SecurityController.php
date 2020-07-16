<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/inscription", name="security_registration")
     */

     public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)// J'appelle en argument la classe request pour éxecuter la requete enBDD
     {
        $users = new Users();

        $form = $this->createForm(RegistrationType::class, $users); // Appel de la class qui permet la construction du formulaire qui est relié à l'entité users

        // handleRequest récupère toutes les données saisies dans le formulaire et les envoit directment dans les setteurs de l'objet $user
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())

        {
            $hash = $encoder->encodePassword($users, $users->getPassword());//On lui demande d'encoder le mdp

            $users->setPassword($hash); //Appel du setteur du mot de passe , on lui demande de le hacher
            
            $manager->persist($users); // On fait persister dans le temps l'utilisateur
            $manager->flush();// On lance la requête d'insertion

           return $this->redirectToRoute('security_login');
        }
        

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);
     }


     /**
      * @Route("/connexion", name="security_login")
      */

      public function login(AuthenticationUtils $authenticationUtils): Response

      {
          //Affiche le message d'erreur
          $error = $authenticationUtils->getLastAuthenticationError();
          //récupère le dernier username saisi par l'internaute
          $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',[
            'last_username' =>$lastUsername,
            'error' => $error
        ]);

      }

      /**
       * @Route("/deconnexion", name="security_logout")
       */

       public function logout() {


       }



}
