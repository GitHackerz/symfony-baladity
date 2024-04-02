<?php

namespace App\Controller;

use App\Entity\DemandeAssociation;
use App\Form\DemandeAssociationType;
use App\Repository\DemandeAssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/demande-association')]
class DemandeAssociationFrontController extends AbstractController
{
    #[Route('', name: 'demande_association_front_index', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demandeAssociation = new DemandeAssociation();
        $form = $this->createForm(DemandeAssociationType::class, $demandeAssociation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandeAssociation);
            $entityManager->flush();

            return $this->redirectToRoute('demande_association_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/demande_association/new.html.twig', [
            'demande_association' => $demandeAssociation,
            'form' => $form,
        ]);
    }
}
