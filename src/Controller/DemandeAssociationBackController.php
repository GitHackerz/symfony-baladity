<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\DemandeAssociation;
use App\Form\DemandeAssociationType;
use App\Repository\DemandeAssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/demande-association')]
class DemandeAssociationBackController extends AbstractController
{
    #[Route('/', name: 'demande_association_back_index', methods: ['GET'])]
    public function index(DemandeAssociationRepository $demandeAssociationRepository): Response
    {
        return $this->render('back/demande_association/index.html.twig', [
            'demande_associations' => $demandeAssociationRepository->findAll(),
        ]);
    }

//    #[Route('/new', name: 'demande_association_back_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $demandeAssociation = new DemandeAssociation();
//        $form = $this->createForm(DemandeAssociationType::class, $demandeAssociation);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($demandeAssociation);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('demande_association_back_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->renderForm('back/demande_association/new.html.twig', [
//            'demande_association' => $demandeAssociation,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'demande_association_back_show', methods: ['GET'])]
    public function show(DemandeAssociation $demandeAssociation): Response
    {
        return $this->render('back/demande_association/show.html.twig', [
            'demande_association' => $demandeAssociation,
        ]);
    }

    #[Route('/{id}/approve', name: 'demande_association_back_approve', methods: ['GET'])]
    public function Approve(DemandeAssociation $demandeAssociation, EntityManagerInterface $entityManager): Response
    {
        $association = new Association();
        $association->setAdresse($demandeAssociation->getAdresse());
        $association->setNom($demandeAssociation->getNom());
        $association->setCaisse($demandeAssociation->getCaisse());
        $association->setType($demandeAssociation->getType());
        $association->setStatut(true);

        $entityManager->persist($association);
        $entityManager->remove($demandeAssociation);
        $entityManager->flush();

        return $this->redirectToRoute('demande_association_back_index', [], Response::HTTP_SEE_OTHER);

    }


    #[Route('/{id}/delete', name: 'demande_association_back_delete', methods: ['GET'])]
    public function delete(Request $request, DemandeAssociation $demandeAssociation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($demandeAssociation);
        $entityManager->flush();

        return $this->redirectToRoute('demande_association_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
