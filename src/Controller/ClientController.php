<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\CreateProjectFormType;
use App\Form\EditProjectFormType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/* On prépare le nom des routes de la classe ClientController */
#[Route('/projet', name: 'project_')]
class ClientController extends AbstractController
{
    /* Contrôleur de la vue "liste" */
    #[Route('/liste/', name: 'list')]
    #[Security("is_granted('ROLE_CONSULTANT')")]
    public function projectList(Request $request, PaginatorInterface $paginator): Response
    {
        // TODO : STAGE gérer entièrement le paginator
        // Récupération du numéro de page
        $requestedPage = $request->query->getInt('page', 1);

        // Vérification que le numéro est positif
        if ($requestedPage < 1) {
            throw new NotFoundHttpException();
        }

        // Ajout à la BDD
        $em = $this->getDoctrine()->getManager();

        // requête pour récupérer tous les projets
        $query = $em->createQuery('SELECT p FROM App\Entity\Project p ORDER BY p.createdAt DESC');

        // Récupération des projets
        $projets = $paginator->paginate(
            $query,
            $requestedPage,
            10
        );

        // Retour à la page de la liste des projets
        return $this->render('project/projectList.html.twig', [
            'projects' => $projets,
        ]);
    }

    /* Contrôleur de la vue "view" */
    #[Route('/afficher/{slug}/', name: 'view')]
    public function projectView(Project $project): Response
    {
        // TODO : STAGE contrôler que personne ne puisse voir les projets des autres clients par l'url
        return $this->render('project/projectView.html.twig', [
            'project' => $project,
        ]);
    }

    /* Contrôleur de la vue "edit" */
    #[Route('/modifier/{slug}/', name: 'edit')]
    public function projectEdit(Project $project, Request $request): Response
    {
        // TODO : STAGE contrôler que personne ne puisse modifier les projets des autres clients par l'url
        // Création du formulaire de modification de service et réinjection de la requête
        $form = $this->createForm(EditProjectFormType::class, $project);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if($form->isSubmitted() && $form->isValid()){

            // Gestion de l'envoi des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Projet modifié avec succès !');

            // Redirection sur la vue détaillée du projet
            return $this->redirectToRoute('project_view', [
                'slug' => $project->getSlug(),
            ]);
        }

        // Envoi de l'utilisateur sur la page d'édition du projet
        return $this->render('project/projectEdit.html.twig', [
            'form' => $form->createView(),
            'slug' => $project->getSlug()
        ]);
    }

    /* Contrôleur de la vue "delete" */
    #[Route('/supprimer/{id}/', name: 'delete')]
    public function projectDelete(Project $project, Request $request): Response
    {
        // TODO : STAGE contrôler que personne ne puisse modifier les projets des autres clients par l'url
        // Contrôle du token csrf
        if(!$this->isCsrfTokenValid('project_delete_' . $project->getId(), $request->query->get('csrf_token'))){

            // Message flash
            $this->addFlash('error', 'Token sécurité invalide, veuillez ré-essayer.');
        } else {

            // Gestion de la suppression des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Le projet a été supprimé avec succès !');
        }

        // Redirection sur la page d'interface
        return $this->redirectToRoute('project_interface');
    }

    /* Contrôleur de la vue "create" */
    #[Route('/creer/', name: 'create')]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function projectCreate(Request $request): Response
    {
        // Création du formulaire et réinjection de la requête
        $project = new Project();
        $form = $this->createForm(CreateProjectFormType::class, $project);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'envoi des données en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $project->setUser($this->getUser());
            $entityManager->persist($project);
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', 'Votre projet à été créé avec succès !');

            // Redirection sur la page d'interface client
            return $this->redirectToRoute('project_interface');
        }

        // Envoi de l'utilisateur sur la page de création de projet
        return $this->render('project/projectCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /* Contrôleur de la vue "interface" */
    #[Route('/interface-client/', name: 'interface')]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function clientInterface(): Response
    {
        // Récupération de tous les projet créés par l'utilisateur actuel
        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $projects = $projectRepo->findBy(['user'=>$this->getUser()]);

        // Envoi de l'utilisateur sur la page d'interface client
        return $this->render('project/clientInterface.html.twig', [
            'projects' => $projects
        ]);
    }

}
