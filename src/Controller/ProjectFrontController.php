<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\User;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project')]
class ProjectFrontController extends AbstractController
{
    #[Route('', name: 'project_front_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository, ): Response
    {
        $participatedProjects = array_map(fn($project) => $project->getId(), $projetRepository->findProjectsByUser(1));

        return $this->render('front/project/index.html.twig', [
            'projects' => $projetRepository->findAll(),
            'participatedProjects' => $participatedProjects
        ]);
    }

    #[Route('/participer/{id}/{user_id}', name: 'app_projet_participer', methods: ['POST'])]
    public function participer(Projet $projet, int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($user_id);

        $projet->addUser($user);

        $entityManager->persist($projet);
        $entityManager->flush();

        return $this->redirectToRoute('project_front_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/quitter/{id}/{user_id}', name: 'app_projet_quitter', methods: ['POST'])]
    public function quitter(Projet $projet, int $user_id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($user_id);
        var_dump($user_id);
        $projet->removeUser($user);

        $entityManager->persist($projet);
        $entityManager->flush();

        return $this->redirectToRoute('project_front_index', [], Response::HTTP_SEE_OTHER);
    }
}
