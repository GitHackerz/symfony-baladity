<?php

namespace App\Controller;

use App\Entity\DemandeDocument;
use App\Form\DemandeDocumentType;
use App\Repository\DemandeDocumentRepository;
use App\Repository\DocumentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/demande_document')]
class DemandeDocumentBackController extends AbstractController
{
    #[Route('/demandes', name: 'app_demande_document_front_index', methods: ['GET'])]
    public function index(DemandeDocumentRepository $demandeDocumentRepository): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        return $this->render('back/demande_document/index.html.twig', [
            'demande_documents' => $demandeDocumentRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_demande_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, DocumentRepository $doc_rep): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

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
        ]);
    }

    #[Route('/{id}', name: 'app_demande_document_show', methods: ['GET'])]
    public function show(DemandeDocument $demandeDocument): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        return $this->render('demande_document/show.html.twig', [
            'demande_document' => $demandeDocument,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DemandeDocument $demandeDocument, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

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
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        if ($this->isCsrfTokenValid('delete' . $demandeDocument->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demandeDocument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_document_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/demandes/Accept/{id}', name: 'app_demande_document_accept', methods: ['GET', 'POST'])]
    public function Accepter_DemandeDoc(Request $request, DemandeDocument $d_document, DemandeDocumentRepository $demandeDocumentRepository): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $id = $request->attributes->get('id');
        $d_documentdocument = $demandeDocumentRepository->find($id);
        $d_documentdocument->setStatut("acceptée");
        $currentDate = new DateTime();
        $d_documentdocument->setDateTraitement($currentDate->format('Y-m-d H:i'));
        $demandeDocumentRepository->Gerer_demande($d_documentdocument);

        return $this->redirectToRoute('app_demande_document_front_index', [], Response::HTTP_SEE_OTHER);

    }


    #[Route('/demandes/Reject/{id}', name: 'app_demande_document_reject', methods: ['GET', 'POST'])]
    public function Rejeter_DemandeDoc(Request $request, DemandeDocument $d_document, DemandeDocumentRepository $demandeDocumentRepository): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $id = $request->attributes->get('id');
        $d_documentdocument = $demandeDocumentRepository->find($id);
        $d_documentdocument->setStatut("rejetée");
        $currentDate = new DateTime();
        $d_documentdocument->setDateTraitement($currentDate->format('Y-m-d H:i'));
        $demandeDocumentRepository->Gerer_demande($d_documentdocument);

        return $this->redirectToRoute('app_demande_document_front_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/demandes/Refactor/{id}', name: 'app_demande_document_refactor', methods: ['GET', 'POST'])]
    public function Refactoriser_demandeDoc(Request $request, DemandeDocument $d_document, DemandeDocumentRepository $demandeDocumentRepository): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $id = $request->attributes->get('id');
        $d_documentdocument = $demandeDocumentRepository->find($id);
        $d_documentdocument->setStatut("en attente");
        $d_documentdocument->setDateTraitement("NA");
        $demandeDocumentRepository->Gerer_demande($d_documentdocument);

        return $this->redirectToRoute('app_demande_document_front_index', [], Response::HTTP_SEE_OTHER);

    }
}
