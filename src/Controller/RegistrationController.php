<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /* Contrôleur de la vue "inscription" */
    #[Route('/inscription/', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        // Si l'utilisateur est connecté on le redirige sur l'accueil
        if($this->getUser()) {
            return $this->redirectToRoute('main_home');
        }

        // Création du formulaire et réinjection de la requête
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // TODO : STAGE gestion du captcha
            // TODO : STAGE confirmation email par mail
            // TODO : STAGE Possibilité de changer le mot de passe en cas d'oubli

            // Hashage du password
            $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
            );

            // Attribution du role USER
            $user->setRoles(["ROLE_USER"]);

            // Gestion de l'envoi des données en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', 'Votre compte à été créé avec succès !');

            // Redirection sur la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Envoi de l'utilisateur sur la page d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
