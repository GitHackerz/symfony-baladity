<?php

namespace App\Controller;

use App\Repository\DemandeDocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DemandeDocumentRepository $ddoc_repo): Response
    {

        return $this->render('back/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'nb_ddoc_accepted' => $ddoc_repo->countAcceptedDDocuments(),
            'nb_ddoc_rejected' => $ddoc_repo->countRejectedDDocuments(),
            'nb_ddoc_pending' => $ddoc_repo->countPendingDDocuments(),

        ]);
    }
    #[Route('/front', name: 'front')]
    public function index2(): Response
    {
        return $this->render('front/user/frontpagelogin.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
