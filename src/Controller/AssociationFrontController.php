<?php

namespace App\Controller;

use App\Entity\Association;
use App\Repository\AssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/associations')]
class AssociationFrontController extends AbstractController
{
    #[Route('/', name: 'association_front_index', methods: ['GET'])]
    public function index(AssociationRepository $associationRepository): Response
    {
        $associations = $associationRepository->findAll();
        
        return $this->render('front/association/index.html.twig', [
            'associations' => $associations,
        ]);
    }

    #[Route('/{id}', name: 'association_front_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->render('front/association/show.html.twig', [
            'association' => $association,
        ]);
    }
}
