<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\User;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project')]
class ProjectFrontController extends AbstractController
{
    #[Route('', name: 'project_front_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {
        $participatedProjects = array_map(fn($project) => $project->getId(), $projetRepository->findProjectsByUser(2));
        return $this->render('front/project/index.html.twig', [
            'projects' => $projetRepository->findAll(),
            'participatedProjects' => $participatedProjects
        ]);
    }

    #[Route('/participer/{id}', name: 'app_projet_participer', methods: ['POST'])]
    public function participer(Projet $projet, EntityManagerInterface $entityManager, MailerService $mailerService): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $user = $this->getUser();

        $projet->addUser($user);
        $entityManager->persist($projet);
        $entityManager->flush();

        $recipient = $user->getEmail();
        $subject = 'Event Participation';
        $body = '
            <p>Dear ' . $user->getCitoyen()->getNom() . ',</p>
            <p>You have successfully participated in the event <span style="font-weight: bold;">' . $projet->getTitre() . '</span></p>
            <p>Thank you for your participation.</p>
        ';

        $mailerService->sendEmail($recipient, $subject, $body);

        return $this->redirectToRoute('project_front_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/quitter/{id}', name: 'app_projet_quitter', methods: ['POST'])]
    public function quitter(Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $user = $this->getUser();
        $projet->removeUser($user);

        $entityManager->persist($projet);
        $entityManager->flush();

        return $this->redirectToRoute('project_front_index', [], Response::HTTP_SEE_OTHER);
    }
}
