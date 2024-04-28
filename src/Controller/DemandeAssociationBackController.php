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

    #[Route('/{id}', name: 'demande_association_back_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeAssociation $demandeAssociation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeAssociation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demandeAssociation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('demande_association_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
