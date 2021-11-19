<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\CreateServiceFormType;
use App\Form\EditServiceFormType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/* On prépare le nom des routes de la classe ConsultantController */
#[Route('/service', name: 'service_')]
class ConsultantController extends AbstractController
{
    /* Contrôleur de la vue "liste" */
    #[Route('/liste/', name: 'list')]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function serviceList(Request $request, PaginatorInterface $paginator): Response
    {
        // Récupération du numéro de page
        $requestedPage = $request->query->getInt('page', 1);

        // Vérification que le numéro est positif
        if ($requestedPage < 1) {
            throw new NotFoundHttpException();
        }

        // Ajout à la BDD
        $em = $this->getDoctrine()->getManager();

        // requête pour récupérer tous les services
        $query = $em->createQuery('SELECT s FROM App\Entity\Service s ORDER BY s.createdAt DESC');

        // Récupération des services
        $services = $paginator->paginate(
            $query,
            $requestedPage,
            3
        );

        // Retour à la page de la liste des services
        return $this->render('service/serviceList.html.twig', [
            'services' => $services,
        ]);
    }

    /* Contrôleur de la vue "view" */
    #[Route('/afficher/{slug}/', name: 'view')]
    public function serviceView(Service $service): Response
    {
        return $this->render('service/serviceView.html.twig', [
            'service' => $service,
        ]);
    }

    /* Contrôleur de la vue "edit" */
    #[Route('/modifier/{slug}/', name: 'edit')]
    public function serviceEdit(Service $service, Request $request): Response
    {
        // TODO : STAGE contrôler que personne ne puisse modifier les services des autres consultants par l'url
        // Création du formulaire de modification de service et réinjection de la requête
        $form = $this->createForm(EditServiceFormType::class, $service);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if($form->isSubmitted() && $form->isValid()){

            // Gestion de l'envoi des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Service modifié avec succès !');

            // Redirection sur la vue détaillée du service
            return $this->redirectToRoute('service_view', [
                'slug' => $service->getSlug(),
            ]);
        }

        // Envoi de l'utilisateur sur la page d'édition du service
        return $this->render('service/serviceEdit.html.twig', [
            'form' => $form->createView(),
            'slug' => $service->getSlug()
        ]);
    }

    /* Contrôleur de la vue "delete" */
    #[Route('/supprimer/{id}/', name: 'delete')]
    public function serviceDelete(Service $service, Request $request): Response
    {
        // TODO : STAGE contrôler que personne ne puisse modifier les services des autres consultants par l'url
        // Contrôle du token csrf
        if(!$this->isCsrfTokenValid('service_delete_' . $service->getId(), $request->query->get('csrf_token'))){

            // Message flash
            $this->addFlash('error', 'Token sécurité invalide, veuillez ré-essayer.');
        } else {

            // Gestion de la suppression des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Le service a été supprimé avec succès !');
        }

        // Redirection sur la page d'interface
        return $this->redirectToRoute('service_interface');
    }

    /* Contrôleur de la vue "create" */
    #[Route('/creer/', name: 'create')]
    public function serviceCreate(Request $request): Response
    {
        // Création du formulaire et réinjection de la requête
        $service = new Service();
        $form = $this->createForm(CreateServiceFormType::class, $service);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'envoi des données en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $service->setUser($this->getUser());
            $entityManager->persist($service);
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', 'Votre service à été créé avec succès !');

            // Redirection sur la page d'interface consultant
            return $this->redirectToRoute('service_interface');
        }

        // Envoi de l'utilisateur sur la page de création de service
        return $this->render('service/serviceCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /* Contrôleur de la vue "interface" */
    #[Route('/interface-consultant/', name: 'interface')]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function consultantInterface(): Response
    {
        // Récupération de tous les services créés par l'utilisateur actuel
        $serviceRepo = $this->getDoctrine()->getRepository(Service::class);
        $services = $serviceRepo->findBy(['user'=>$this->getUser()]);

        // Envoi de l'utilisateur sur la page d'interface consultant
        return $this->render('service/consultantInterface.html.twig', [
            'services' => $services
        ]);
    }
}
