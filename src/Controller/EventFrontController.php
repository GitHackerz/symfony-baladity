<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\User;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/participer/{id}/{user_id}', name: 'app_evenement_participer', methods: ['POST'])]
    public function participer(Evenement $evenement, int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find(2git add );

        $evenement->addUser($user);

        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('event_front_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quitter/{id}/{user_id}', name: 'app_evenement_quitter', methods: ['POST'])]
    public function quitter(Evenement $evenement, int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($user_id);

        $evenement->removeUser($user);

        $entityManager->persist($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('event_front_index', [], Response::HTTP_SEE_OTHER);
    }
}
