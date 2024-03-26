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

#[Route('/demande/association')]
class DemandeAssociationController extends AbstractController
{
    #[Route('/', name: 'app_demande_association_index', methods: ['GET'])]
    public function index(DemandeAssociationRepository $demandeAssociationRepository): Response
    {
        return $this->render('demande_association/index.html.twig', [
            'demande_associations' => $demandeAssociationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_demande_association_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demandeAssociation = new DemandeAssociation();
        $form = $this->createForm(DemandeAssociationType::class, $demandeAssociation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandeAssociation);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_association_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande_association/new.html.twig', [
            'demande_association' => $demandeAssociation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_association_show', methods: ['GET'])]
    public function show(DemandeAssociation $demandeAssociation): Response
    {
        return $this->render('demande_association/show.html.twig', [
            'demande_association' => $demandeAssociation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_association_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeAssociation $demandeAssociation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeAssociationType::class, $demandeAssociation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_association_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande_association/edit.html.twig', [
            'demande_association' => $demandeAssociation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_association_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeAssociation $demandeAssociation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeAssociation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demandeAssociation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_association_index', [], Response::HTTP_SEE_OTHER);
    }
}
