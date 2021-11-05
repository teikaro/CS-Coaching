<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/* On prépare le nom des routes de la classe MainController */
#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    /* Contrôleur de la vue home */
    #[Route('/', name: 'home')]
    public function home(): Response
    {

        return $this->render('main/home.html.twig');
    }

    /*  Page Coaching Pro */

     #[Route("/coachingPro", name:"coachingPro")]

    public function coachingPro(): Response
    {
        return $this->render('main/coachingPro.html.twig');
    }

    /*  Page deroulement d'un Coaching pro */

    #[Route("/deroulement-et-modalites-d'un-coachingPro", name:"deroulement_coachingPro")]

    public function deroulement_coachingPro(): Response
    {
        return $this->render('main/deroulement_coachingPro.html.twig');
    }

    /*  Page deroulement d'un Coaching scolaire et etudiant */

    #[Route("/coaching-scolaire-et-etudiant", name:"coachingScolaire")]

    public function coachingScolaire(): Response
    {
        return $this->render('main/coachingScolaire.html.twig');
    }

    /*  Page Mentions légales Coaching scolaire et etudiant */

    #[Route("/Mentions-légales-coaching-scolaire-et-etudiant", name:"Mentions_coachingScolaire")]

    public function Mentions_coachingScolaire(): Response
    {
        return $this->render('main/Mentions_coachingScolaire.html.twig');
    }

    /*  Page Orientation / reconversion */

    #[Route("/Module-orientation/reconversion", name:"orientation_reconversion")]

    public function orientation_reconversion(): Response
    {
        return $this->render('main/orientation_reconversion.html.twig');
    }



    /*  Page mes partenaires */

    #[Route("/mes partenaires", name:"partenaires")]

    public function partenaires(): Response
    {
        return $this->render('main/partenaires.html.twig');
    }

    /*  Page revues de presses */

    #[Route("/mes-articles-et-revues-de-presses ", name:"revuesPresses")]

    public function revues(): Response
    {
        return $this->render('main/RevuesPresses.html.twig');
    }

    /*  Page qui suis-je? */

    #[Route("/A-propos-de-moi ", name:"qui_suis_je")]

    public function qui_suis_je(): Response
    {
        return $this->render('main/qui_suis_je.html.twig');
    }







    /* Contrôleur de la vue Nos missions */
    #[Route('/nos-mission/', name: 'missions')]
    public function missions(): Response
    {
        return $this->render('main/missions.html.twig');
    }



    /* Contrôleur de la vue a propose */
    #[Route('/a-propos/', name: 'qui_suis_je')]
    public function about(): Response
    {
        return $this->render('main/qui_suis_je.html.twig');
    }

    /* Contrôleur de la vue contact */
    #[Route('/contactez-nous/', name: 'contact')]
    public function contact(): Response
    {
        /* Liens temporaire : page en construction */
        /* TODO : Créer une page de contact avec formulaire et envoi de mail */
        return $this->render('wip/wip.html.twig');
    }
}
