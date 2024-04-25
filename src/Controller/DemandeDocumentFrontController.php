<?php

namespace App\Controller;

use App\Entity\DemandeDocument;
use App\Form\DemandeDocumentType;
use App\Repository\DemandeDocumentRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/document/demandes')]
class DemandeDocumentFrontController extends AbstractController
{

    #[Route('', name: 'app_demande_document_index', methods: ['GET'])]
    public function index(DemandeDocumentRepository $demandeDocumentRepository): Response
    {
        return $this->render('front/document/demande_document/index.html.twig', [
            'demande_documents' => $demandeDocumentRepository->find_Aceepted_Rejected(),
        ]);
    }


    #[Route('/new/{id}', name: 'app_demande_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , DocumentRepository $doc_rep): Response
    {
        $id = $request->attributes->get('id');

        $demandeDocument = new DemandeDocument();
        $form = $this->createForm(DemandeDocumentType::class, $demandeDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandeDocument);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/document/demande_document/new.html.twig', [
            'demande_document' => $demandeDocument,
            'form' => $form,
            'doc' => $doc_rep->find($id),
            'id_doc' => $id,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_document_show', methods: ['GET'])]
    public function show(DemandeDocument $demandeDocument): Response
    {
        return $this->render('demande_document/show.html.twig', [
            'demande_document' => $demandeDocument,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeDocument $demandeDocument, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeDocumentType::class, $demandeDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demande_document/edit.html.twig', [
            'demande_document' => $demandeDocument,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_document_delete', methods: ['POST'])]
    public function delete(Request $request, DemandeDocument $demandeDocument, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeDocument->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demandeDocument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_document_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/mondocument/{id}', name: 'app_monDocument_Espace', methods: ['GET'])]
    public function monDocumentEspace(Request $request, EntityManagerInterface $entityManager , DemandeDocumentRepository $ddoc_rep): Response
    {

        $id = $request->attributes->get('id');

        return $this->render('front/document/mon_Document.html.twig', [
            'demande' => $ddoc_rep->find($id),
        ]);
    }




}
