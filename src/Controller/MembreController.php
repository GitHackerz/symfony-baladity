<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/membre')]
class MembreController extends AbstractController
{
    #[Route('/', name: 'membre_back_index', methods: ['GET'])]
    public function index(MembreRepository $membreRepository): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $membresByEvent = $membreRepository->findCountByEvent();
        return $this->render('back/membre/index.html.twig', [
            'membres' => $membreRepository->findAll(),
            'membresByEvent' => $membresByEvent,
        ]);
    }

    #[Route('/new', name: 'membre_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('membre_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/membre/new.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'membre_back_show', methods: ['GET'])]
    public function show(Membre $membre): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        return $this->render('back/membre/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    #[Route('/{id}/edit', name: 'membre_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Membre $membre, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('membre_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'membre_back_delete', methods: ['POST'])]
    public function delete(Request $request, Membre $membre, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($membre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('membre_back_index', [], Response::HTTP_SEE_OTHER);
    }


}