<?php

namespace App\Controller;

use App\Repository\CitoyenRepository;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $userRepository, CitoyenRepository $citoyenRepository, ProjetRepository $projetRepository): Response
    {
        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $userRepository->findAll(),
            'citoyens' => $citoyenRepository->findAll(),
            'projets' => $projetRepository->findAll(),
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        return $this->render('front/user/profile.html.twig');
    }
}
