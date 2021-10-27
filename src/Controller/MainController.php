<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    /* Contrôleur de la vue Nos missions */
    #[Route('/nos-mission/', name: 'missions')]
    public function missions(): Response
    {
        return $this->render('main/missions.html.twig');
    }

    /* Contrôleur de la vue qui sommes nous */
    #[Route('/qui-sommes-nous/', name: 'who_are_we')]
    public function whoAreWe(): Response
    {
        return $this->render('main/whoAreWe.html.twig');
    }

    /* Contrôleur de la vue a propose */
    #[Route('/a-propos/', name: 'about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig');
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
