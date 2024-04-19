<?php

namespace App\Controller;

use App\Entity\DemandeDocument;
use App\Entity\Document;
use App\Form\DemandeDocumentType;
use App\Form\DocumentType;
use App\Repository\DemandeDocumentRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/document')]
class DocumentFrontController extends AbstractController
{
    #[Route('/', name: 'app_demande_doc')]
    public function index(DocumentRepository $documentRepository): Response
    {
        $documents = $documentRepository->findByEstArchive(false);
        return $this->render('front/document/index.html.twig', [
            'controller_name' => 'DocumentFrontController',
            'documents' => $documents
        ]);
    }




   /* #[Route('/demande_doc/new', name: 'app_demande_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demanadeDoc = new DemandeDocument();
        $form = $this->createForm(DemandeDocumentType::class, $demanadeDoc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demanadeDoc);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_document_new', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('front/document/demande_document/new.html.twig', [
            'demande_doc' => $demanadeDoc,
            'form' => $form,
        ]);
    }*/

}
