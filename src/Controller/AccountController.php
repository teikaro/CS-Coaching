<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/* On prépare le nom des routes de la classe AccountController */
#[Route('/compte', name: 'account_')]
#[Security("is_granted('ROLE_USER')")]
class AccountController extends AbstractController
{
    /* Contrôleur de la vue "account" */
    #[Route('/', name: 'show')]
    public function show(): Response
    {
        return $this->render('account/show.html.twig');
    }

    /* Contrôleur de la vue "edit" */
    #[Route('/{lastName}/edit', name: 'edit', methods: ['GET','POST'])]
    public function edit(Request $request, User $user): Response
    {
        // Création du formulaire et réinjection de la requête
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'envoi des données en base de données
            $this->getDoctrine()->getManager()->flush();

            // Message flash
            $this->addFlash('success', 'Votre compte a été modifié avec succès !');

            // Redirection sur la page de gestion de compte
            return $this->redirectToRoute('account_show');
        }

        // Envoi de l'utilisateur sur la page d'edition du compte
        return $this->renderForm('account/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /* Contrôleur de la vue "delete" */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        // Contrôle du token csrf
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {

            // Gestion de la suppression des données en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $this->container->get('security.token_storage')->setToken(null);
            $entityManager->remove($user);
            $entityManager->flush();
        }

        // Message flash
        $this->addFlash('success', 'Votre compte a été supprimé avec succès !');

        // Redirection sur la page de d'accueil
        return $this->redirectToRoute('main_home');
    }

    // TODO : STAGE créer un formulaire de contact
    /* Contrôleur de la vue "profil" */
    #[Route('/{id}/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('wip/wip.html.twig');
    }
}
