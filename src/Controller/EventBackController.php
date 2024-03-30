<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/event')]
class EventBackController extends AbstractController
{
    #[Route('/', name: 'event_back_index', methods: ['GET'])]
    public function index(EvenementRepository $eventRepository): Response
    {
        return $this->render('back/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'event_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Evenement();
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_back_show', methods: ['GET'])]
    public function show(Evenement $event): Response
    {
        return $this->render('back/event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'event_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('event_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_back_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_back_index', [], Response::HTTP_SEE_OTHER);
    }
}