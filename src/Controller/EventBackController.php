<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

//use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/dashboard/event')]
class EventBackController extends AbstractController
{

    #[Route('/', name: 'event_back_index', methods: ['GET'])]
    public function index(EvenementRepository $eventRepository): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        return $this->render('back/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/generate-pdf', name: 'event_generate_pdf', methods: ['GET'])]
    public function generatePdf(EvenementRepository $eventRepository): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        // Récupérer la liste des événements
        $events = $eventRepository->findAll();

        // Créer une instance de Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        // Générer le contenu HTML pour le PDF
        $html = $this->renderView('back/event/pdf.html.twig', [
            'events' => $events,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Renvoyer la réponse avec le PDF en tant que fichier téléchargeable
        $output = $dompdf->output();
        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="events.pdf"',
        ]);
    }

    #[Route('/new', name: 'event_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $event = new Evenement();
        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_back_show', methods: ['GET'])]
    public function show(Evenement $event): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $paricipants = $event->getUser();
        return $this->render('back/event/show.html.twig', [
            'event' => $event,
            'participants' => $paricipants
        ]);
    }

    #[Route('/{id}/edit', name: 'event_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $event, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $form = $this->createForm(EvenementType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('event_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'event_back_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $event, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_back_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/', name: 'event_back_search', methods: ['GET'])]
    public function search(Request $request, EvenementRepository $eventRepository): JsonResponse
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $searchTerm = $request->query->get('q');

        // Implémentez la logique de recherche en utilisant le terme de recherche
        // Utilisez la méthode appropriée dans votre repository
        $events = $eventRepository->findByTitle($searchTerm);

        // Créez un tableau associatif contenant les données à renvoyer
        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'id' => $event->getId(),
                'titre' => $event->getTitre(),
                'description' => $event->getDescription(),
                'date' => $event->getDate(),
                'lieu' => $event->getLieu(),
                'nomContact' => $event->getNomContact(),
                'emailContact' => $event->getEmailContact(),
                'statut' => $event->isStatut(),
            ];
        }

        // Renvoyez les données au format JSON
        return new JsonResponse($data);
    }
}