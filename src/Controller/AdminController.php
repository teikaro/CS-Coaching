<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/* On prépare le nom des routes de la classe ResumeController */
#[Route('/admin', name: 'admin_')]
/* On met en place les contrôle d'accès des routes de la classe ResumeController */
#[IsGranted("ROLE_ADMIN")]
class AdminController extends AbstractController
{

    /* Contrôleur de la vue "index" */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
            'titre' => 'profils'
        ]);
    }

    /* Contrôleur de la vue "manager" */
    #[Route('/manager', name: 'manager')]
    public function managerList(UserRepository $userRepository): Response
    {

        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findByRole('ROLE_MANAGER'),
            'titre' => 'managers'
        ]);
    }

    /* Contrôleur de la vue "client" */
    #[Route('/client', name: 'client')]
    public function clientList(UserRepository $userRepository): Response
    {

        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findByRole('ROLE_CLIENT'),
            'titre' => 'clients'
        ]);
    }

    /* Contrôleur de la vue "consultant" */
    #[Route('/consultant', name: 'consultant')]
    public function consultantList(UserRepository $userRepository): Response
    {

        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findByRole('ROLE_CONSULTANT'),
            'titre' => 'consultants'
        ]);
    }

    /* Contrôleur de la vue "user" */
    #[Route('/user', name: 'user')]
    public function userList(UserRepository $userRepository): Response
    {

        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findByRole('ROLE_USER'),
            'titre' => 'profils en attente'
        ]);
    }

    /* Contrôleur de la vue "user_edit" */
    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET','POST'])]
    public function userEdit(Request $request, User $user): Response
    {
        // Création du formulaire et réinjection de la requête
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'envoi des données en base de données
            $this->getDoctrine()->getManager()->flush();

            // Message flash
            $this->addFlash('success', 'Le rôle de '. $user->getFirstName() .' '. $user->getLastName() .' a été modifié avec succès !');

            // Redirection sur la vue index
           return $this->redirectToRoute('admin_index');
        }

        // Envoi de l'utilisateur sur la page d'edition
        return $this->renderForm('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /* Contrôleur de la vue "user_delete" */
    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        // Contrôle du token csrf
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // Gestion de la suppression des données en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', 'Le compte de '. $user->getFirstName() .' '. $user->getLastName() .' a été supprimé avec succès !');
        }

        // Redirection sur la vue index
        return $this->redirectToRoute('admin_index');
    }
}
