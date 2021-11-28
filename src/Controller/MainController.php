<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/* On prépare le nom des routes de la classe MainController */
#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    /* Contrôleur de la vue "home" */
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

    /*  Page CGU  */

    #[Route("/CGU", name:"cgu")]

    public function cgu(): Response
    {
        return $this->render('main/cgu.html.twig');
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

    /* Contrôleur de la vue "Nos missions" */
    #[Route('/nos-mission/', name: 'missions')]
    public function missions(): Response
    {
        return $this->render('main/missions.html.twig');
    }



    /* Contrôleur de la vue "contact" */
    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/contactez-nous/', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $contact = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('varraut.corentin@gmail.com')
                ->subject('Contact depuis le site CS Coaching')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'mail' => $contact->get('email')->getData(),
                    'sujet' => $contact->get('sujet')->getData(),
                    'message' => $contact->get('message')->getData()
                ])
            ;


            $mailer->send($email);

            $this->addFlash('message', 'mail de contact envoyé !');
            return $this->redirectToRoute('main_contact');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}


