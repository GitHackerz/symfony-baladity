<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\TacheProjet;
use App\Entity\User;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/dashboard/project')]
class ProjectBackController extends AbstractController
{
    #[Route('/', name: 'project_back_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('back/project/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'project_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('project_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/project/new.html.twig', [
            'project' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'project_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('project_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/project/edit.html.twig', [
            'project' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'project_back_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('back/project/show.html.twig', [
            'project' => $projet,
        ]);
    }

    #[Route('/{id}', name: 'project_back_delete', methods: ['DELETE'])]
    public function delete(Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($projet);
        $entityManager->flush();


        return $this->redirectToRoute('project_back_index', [], Response::HTTP_SEE_OTHER);
    }


}
