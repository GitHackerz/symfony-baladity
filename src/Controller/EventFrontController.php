<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\User;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

#[Route('/event')]
class EventFrontController extends AbstractController
{
    #[Route('/', name: 'event_front_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        // Récupérer les identifiants des événements auxquels l'utilisateur a déjà participé
        $participatedEvents = array_map(fn($event) => $event->getId(), $evenementRepository->findEventsByUser(1));

        return $this->render('front/evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'participatedEvents' => $participatedEvents, // Passer les identifiants des événements participés au template
        ]);
    }

    #[Route('/participer/{id}', name: 'app_evenement_participer', methods: ['POST'])]
    public function participer(Evenement $evenement, EntityManagerInterface $entityManager,TexterInterface $texter): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $smsMessage = "Tu as un l'evenement  " . $evenement->getTitre() . " (" . $evenement->getDate() . ")";
        $sms = new SmsMessage("+21658906040", $smsMessage);
        $texter->send($sms);

        $evenement->addUser($this->getUser());

        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('event_front_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quitter/{id}', name: 'app_evenement_quitter', methods: ['POST'])]
    public function quitter(Evenement $evenement,  EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $evenement->removeUser($this->getUser());

        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('event_front_index', [], Response::HTTP_SEE_OTHER);
    }
}