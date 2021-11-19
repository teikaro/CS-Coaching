<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /* Sur ce contrôleur, on évite les attributs, et on préférera les annotations du fait que tout n'est pas encore pris en charge */

    /* Contrôleur de la vue "connexion" */
    /**
     * @Route("/connexion/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est connecté on le redirige sur l'accueil
         if ($this->getUser()) {
             return $this->redirectToRoute('main_home');
         }

        // Récupération de l'erreur s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernière adresse e-mail utilisée par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Envoi de l'utilisateur sur la page de connexion
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /* Contrôleur pour la déconnexion */
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
