<?php

namespace App\Controller;

use App\Repository\CitoyenRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{    
    
    
    
    public  function simplePrediction($totalUsers, $totalCitizens)
    {
        $growthRate = 0.05; // 5%
        $newUsers = $growthRate * $totalCitizens;
        $predictedUsers = $totalUsers + $newUsers;

        return $predictedUsers;
    }
    #[Route('/stats', name: 'app_stats')]
    public function index(UserRepository $userRepository, CitoyenRepository $citizenRepository): Response
    {
        $totalUsers = $userRepository->count([]);

        // Récupérer le nombre total de citoyens
        $totalCitizens = $citizenRepository->count([]);

        // Prédiction simple (ajouter votre logique de prédiction ici)
        $predictedUsers = $this->simplePrediction($totalUsers, $totalCitizens);

        // Retourner les données en JSON par exemple
        return $this->json([
            'totalUsers' => $totalUsers,
            'totalCitizens' => $totalCitizens,
            'predictedUsers' => $predictedUsers,
        ]);


   
    
}
}
