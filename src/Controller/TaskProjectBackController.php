<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\TacheCommentaires;
use App\Entity\TacheProjet;
use App\Entity\User;
use App\Form\TacheProjetType;
use App\Repository\ProjetRepository;
use App\Repository\TacheProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/project-tasks')]
class TaskProjectBackController extends AbstractController
{
    #[Route('/', name: 'task_project_back_index', methods: ['GET'])]
    public function index(TacheProjetRepository $tacheProjetRepository, ProjetRepository $projetRepository): Response
    {
        $tasks = $tacheProjetRepository->findAll();
        $todoTasks = array_filter($tasks, function($task) {
            return $task->getStatut() === 'To Do';
        });
        $inProgressTasks = array_filter($tasks, function($task) {
            return $task->getStatut() === 'In Progress';
        });
        $doneTasks = array_filter($tasks, function($task) {
            return $task->getStatut() === 'Done';
        });
        return $this->render('back/task_project/index.html.twig', [
            'projects' => $projetRepository->findAll(),
            'tache_projets' => $tacheProjetRepository->findAll(),
            'todoTasks' => $todoTasks,
            'inProgressTasks' => $inProgressTasks,
            'doneTasks' => $doneTasks
        ]);
    }

    #[Route('/new', name: 'task_project_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tacheProjet = new TacheProjet();
        $tacheProjet->setStatut('To Do');
        $tacheProjet->setDate(new \DateTime(
            date('Y-m-d H:i', strtotime('tomorrow'))
        ));
        $form = $this->createForm(TacheProjetType::class, $tacheProjet);
        $form->handleRequest($request);
        $projects = $entityManager->getRepository(Projet::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tacheProjet);
            $entityManager->flush();

            return $this->redirectToRoute('task_project_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/task_project/new.html.twig', [
            'tache_projet' => $tacheProjet,
            'form' => $form,
            'users' => $users,
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}', name: 'task_project_back_show', methods: ['GET'])]
    public function show(TacheProjet $tacheProjet): Response
    {
        // get comments of the task sorted by date desc
        $comments = $tacheProjet->getTacheCommentaires()->toArray();
        //sort
        usort($comments, function($a, $b) {
            return $b->getDate() <=> $a->getDate();
        });
        return $this->render('back/task_project/show.html.twig', [
            'tache_projet' => $tacheProjet,
            'comments' => $comments,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_project_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TacheProjet $tacheProjet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TacheProjetType::class, $tacheProjet);
        $form->handleRequest($request);
        $projects = $entityManager->getRepository(Projet::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $tacheProjet->setStatut('To Do');
            $entityManager->persist($tacheProjet);
            $entityManager->flush();

            return $this->redirectToRoute('task_project_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/task_project/edit.html.twig', [
            'tache_projet' => $tacheProjet,
            'form' => $form,
            'users' => $users,
            'projects' => $projects,
        ]);
    }

    // add comment
    #[Route('/{id}/comment', name: 'task_project_back_comment', methods: ['POST'])]
    public function comment(Request $request, TacheProjet $tacheProjet, EntityManagerInterface $entityManager): JsonResponse
    {
        $comment = new TacheCommentaires();
        $comment->setTacheProjet($tacheProjet);
        $comment->setUser($entityManager->getRepository(User::class)->find(10));
        $comment->setContent($request->request->get('content'));
        $comment->setDate(new \DateTime());

        $tacheProjet->addTacheCommentaire($comment);
        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse([
            'user' => $comment->getUser()->getCitoyen()->getNom() . ' ' . $comment->getUser()->getCitoyen()->getPrenom(),
            'image' => $comment->getUser()->getImage(),
            'date' => $comment->getDate()->format('Y-m-d H:i:s'),
            'content' => $comment->getContent()
        ]);
    }

    #[Route('/{id}/start', name: 'task_project_back_start', methods: ['PUT'])]
    public function start(Request $request, TacheProjet $tacheProjet, EntityManagerInterface $entityManager): Response
    {
        $tacheProjet->setStatut('In Progress');
        $entityManager->flush();

        return $this->redirectToRoute('task_project_back_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/done', name: 'task_project_back_done', methods: ['PUT'])]
    public function done(Request $request, TacheProjet $tacheProjet, EntityManagerInterface $entityManager): Response
    {
        $tacheProjet->setStatut('Done');
        $entityManager->flush();

        return $this->redirectToRoute('task_project_back_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'task_project_back_delete', methods: ['DELETE'])]
    public function delete(Request $request, TacheProjet $tacheProjet, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($tacheProjet);
        $entityManager->flush();

        return $this->redirectToRoute('task_project_back_index', [], Response::HTTP_SEE_OTHER);
    }


}
