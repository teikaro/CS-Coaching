<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\CreateArticleFormType;
use App\Form\EditArticleFormType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/* On prépare le nom des routes de la classe ArticleController */
#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    /* Contrôleur de la vue "liste" */
    #[Route('/liste/', name: 'list')]
    public function articleList(Request $request, PaginatorInterface $paginator): Response
    {
        // Récupération du numéro de page
        $requestedPage = $request->query->getInt('page', 1);

        // Vérification que le numéro est positif
        if ($requestedPage < 1) {
            throw new NotFoundHttpException();
        }

        // Ajout à la BDD
        $em = $this->getDoctrine()->getManager();

        // requête pour récupérer tous les projets
        $query = $em->createQuery('SELECT a FROM App\Entity\Article a ORDER BY a.createdAt DESC');

        // Récupération des projets
        $projets = $paginator->paginate(
            $query,
            $requestedPage,
            4
        );

        // Retour à la page de la liste des projets
        return $this->render('article/articleList.html.twig', [
            'articles' => $projets,
        ]);
    }

    /* Contrôleur de la vue "view" */
    #[Route('/afficher/{slug}/', name: 'view')]
    public function articleView(Article $article): Response
    {
        // TODO : STAGE contrôler que personne ne puisse voir les projets des autres clients par l'url
        return $this->render('article/articleView.html.twig', [
            'article' => $article,
        ]);
    }

    /* Contrôleur de la vue "edit" */
    #[Route('/modifier/{slug}/', name: 'edit')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function articleEdit(Article $article, Request $request): Response
    {
        // Création du formulaire de modification de resume et réinjection de la requête
        $form = $this->createForm(EditArticleFormType::class, $article);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if($form->isSubmitted() && $form->isValid()){

            // Gestion de l'envoi des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Cet article à été modifié avec succès !');

            // Redirection sur la vue détaillée du projet
            return $this->redirectToRoute('article_view', [
                'slug' => $article->getSlug(),
            ]);
        }

        // Envoi de l'utilisateur sur la page d'édition du projet
        return $this->render('article/articleEdit.html.twig', [
            'form' => $form->createView(),
            'slug' => $article->getSlug()
        ]);
    }

    /* Contrôleur de la vue "delete" */
    #[Route('/supprimer/{id}/', name: 'delete')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function articleDelete(Article $article, Request $request): Response
    {
        // Contrôle du token csrf
        if(!$this->isCsrfTokenValid('article_delete_' . $article->getId(), $request->query->get('csrf_token'))){

            // Message flash
            $this->addFlash('error', 'Token sécurité invalide, veuillez ré-essayer.');
        } else {

            // Gestion de la suppression des données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Cet article a été supprimé avec succès !');
        }

        // Redirection sur la page d'interface
        return $this->redirectToRoute('article_interface');
    }

    /* Contrôleur de la vue "create" */
    #[Route('/creer/', name: 'create')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function articleCreate(Request $request): Response
    {
        // Création du formulaire et réinjection de la requête
        $article = new Article();
        $form = $this->createForm(CreateArticleFormType::class, $article);
        $form->handleRequest($request);

        // Contrôle sur la validité d'un formulaire envoyé
        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion de l'envoi des données en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $article->setUser($this->getUser());
            $entityManager->persist($article);
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', 'Votre article à été créé avec succès !');

            // Redirection sur la page d'interface client
            return $this->redirectToRoute('article_interface');
        }

        // Envoi de l'utilisateur sur la page de création de projet
        return $this->render('article/articleCreate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /* Contrôleur de la vue "interface" */
    #[Route('/interface-client/', name: 'interface')]
    #[Security("is_granted('ROLE_CLIENT')")]
    public function clientInterface(): Response
    {
        // Récupération de tous les projet créés par l'utilisateur actuel
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articleRepo->findBy(['user'=>$this->getUser()]);

        // Envoi de l'utilisateur sur la page d'interface client
        return $this->render('article/clientInterface.html.twig', [
            'articles' => $articles
        ]);
    }

}
