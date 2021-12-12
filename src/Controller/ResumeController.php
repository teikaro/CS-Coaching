<?php

namespace App\Controller;

use App\Entity\Resume;
use App\Form\CreateResumeFormType;
use App\Form\EditResumeFormType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/* On prépare le nom des routes de la classe ResumeController */
#[Route('/resume', name: 'resume_')]
class ResumeController extends AbstractController
{
    /* Contrôleur de la vue "liste" */
    #[Route('/liste/', name: 'list')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function resumeList(Request $request, PaginatorInterface $paginator): Response
    {
        // Récupération du numéro de page
        $requestedPage = $request->query->getInt('page', 1);

        // Vérification que le numéro est positif
        if ($requestedPage < 1) {
            throw new NotFoundHttpException();
        }

        // Ajout à la BDD
        $em = $this->getDoctrine()->getManager();

        // requête pour récupérer tous les resumes
        $query = $em->createQuery('SELECT r FROM App\Entity\Resume r ORDER BY r.createdAt DESC');

        // Récupération des resumes
        $resumes = $paginator->paginate(
            $query,
            $requestedPage,
            4
        );

        // Retour à la page de la liste des resumes
        return $this->render('resume/resumeList.html.twig', [
            'resumes' => $resumes,
        ]);
    }

    /* Contrôleur de la vue "view" */
    #[Route('/afficher/{slug}/', name: 'view')]
    public function resumeView(Resume $resume): Response
    {
        return $this->render('resume/resumeView.html.twig', [
            'resume' => $resume,
        ]);
    }

    /* Contrôleur de la vue "edit" */
    #[Route('/modifier/{slug}/', name: 'edit')]
    public function resumeEdit(Resume $resume, Request $request): Response
    {
        // Création du formulaire de modification de resume et réinjection de la requête
        $form = $this->createForm(EditResumeFormType::class, $resume);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if($form->isSubmitted() && $form->isValid()){

            // Gestion de l'envoi des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Résumé de séance modifié avec succès !');

            // Redirection sur la vue détaillée du resume
            return $this->redirectToRoute('resume_view', [
                'slug' => $resume->getSlug(),
            ]);
        }

        // Envoi de l'utilisateur sur la page d'édition du resume
        return $this->render('resume/resumeEdit.html.twig', [
            'form' => $form->createView(),
            'slug' => $resume->getSlug()
        ]);
    }

    /* Contrôleur de la vue "delete" */
    #[Route('/supprimer/{id}/', name: 'delete')]
    public function resumeDelete(Resume $resume, Request $request): Response
    {
        // Contrôle du token csrf
        if(!$this->isCsrfTokenValid('resume_delete_' . $resume->getId(), $request->query->get('csrf_token'))){

            // Message flash
            $this->addFlash('error', 'Token sécurité invalide, veuillez ré-essayer.');
        } else {

            // Gestion de la suppression des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($resume);
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Le résumé de séance a été supprimé avec succès !');
        }

        // Redirection sur la page d'interface
        return $this->redirectToRoute('resume_interface');
    }

    /* Contrôleur de la vue "create" */
    #[Route('/creer/', name: 'create')]
    public function resumeCreate(Request $request): Response
    {
        // Création du formulaire et réinjection de la requête
        $resume = new Resume();
        $form = $this->createForm(CreateResumeFormType::class, $resume);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'envoi des données en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $resume->setUser($this->getUser());
            $entityManager->persist($resume);
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', 'Votre resume à été créé avec succès !');

            // Redirection sur la page d'interface consultant
            return $this->redirectToRoute('resume_interface');
        }

        // Envoi de l'utilisateur sur la page de création de resume
        return $this->render('resume/resumeCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /* Contrôleur de la vue "interface" */
    #[Route('/interface-client/', name: 'interface')]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function consultantInterface(): Response
    {
        // Récupération de tous les resumes créés par l'utilisateur actuel
        $resumeRepo = $this->getDoctrine()->getRepository(Resume::class);
        $resumes = $resumeRepo->findBy(['user'=>$this->getUser()]);

        // Envoi de l'utilisateur sur la page d'interface consultant
        return $this->render('resume/consultantInterface.html.twig', [
            'resumes' => $resumes
        ]);
    }
}
