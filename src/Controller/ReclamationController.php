<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Repository\ReponseRepository;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Form\ReclamationType;
use App\Form\RepondreType;
use \DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use App\Repository\ReclamationRepository;
use Endroid\QrCode\Builder\Builder;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\QrCode;



class ReclamationController extends AbstractController
{
    private function sendConfirmationEmail($reservation, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from(new Address('yassmine.layes@gmail.com', 'Pidev'))
            ->to($reservation->getUser()->getEmail())
            ->subject('Confirmation de réservation')
            ->html($this->renderView(
                'reclamation/email.html.twig',
                ['reclamation' => $reservation]
            ));

        $mailer->send($email);
    }
    #[Route('/reclamation/pdf/{id}', name: 'reclamationpdf')]
    public function generatePDF($id,ReclamationRepository $repository): Response
   {

       $reclamation=$repository->find($id) ;
           $pdfContent = $this->renderView('reclamation/pdf.html.twig', [
           'reclamation' => $reclamation,

       ]);

       $options = new Options();
       $options->set('isHtml5ParserEnabled', true);
       $options->set('isRemoteEnabled', true);

       $dompdf = new Dompdf($options);

       $dompdf->loadHtml($pdfContent);


       $dompdf->render();

       return new Response($dompdf->output(), 200, [
           'Content-Type' => 'application/pdf',
           'Content-Disposition' => 'attachment; filename="reclamation.pdf"',
       ]);
   }
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(ReclamationRepository $rp): Response
    {
        $utilisateurId = 1;
        $recl = $rp->findBy(['user' => $utilisateurId]);
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $recl,
        ]);
    }
    #[Route('/reclamation/edit/{id}', name: 'updaterecla')]
    public function edit(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $rec = $entityManager->getRepository(Reclamation::class)->find($id);
        if (!$rec) {
            throw $this->createNotFoundException('No Reclamation found for id ' . $rec->getId());
        }

        $originalImage = $rec->getImage();
        $form = $this->createForm(ReclamationType::class, $rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();

            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                try {
                    $file->move($this->getParameter('images_directory'), $fileName);
                    $rec->setImage($fileName);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'A problem occurred while uploading the file.');
                }
            } else {
                $prop->setImage($originalImage);
            }

            $entityManager->persist($rec);
            $entityManager->flush();

            $this->addFlash('success', 'Reclamation mise à jour avec succès.');
            return $this->redirectToRoute('app_reclamation');
        }

        return $this->render('reclamation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('reclamation/delete/{id}', name: 'Supprimerrecla')]
    public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $recl = $entityManager->getRepository(Reclamation::class)->find($id);
        $entityManager->remove($recl);
        $entityManager->flush();
        return $this->redirectToRoute('app_reclamation');
    }
    #[Route('/reclamationback', name: 'app_reclamationback')]
    public function index2(ReclamationRepository $rp): Response
    {
        $totalReclamations = $rp->count([]);

        $reclamationsPriorite = [
            'faible' => $rp->countByPriorite('faible'),
            'moyenne' => $rp->countByPriorite('moyenne'),
            'haute' => $rp->countByPriorite('haute'),
        ];
    
        $dateDebutMois = new DateTime('first day of this month');
        $dateFinMois = new DateTime('last day of this month');
        $reclamationsCeMois = $rp->countByDate($dateDebutMois, $dateFinMois);
    
        $reclamationsEnCours = $rp->findByStatusEnCours();
    
        return $this->render('reclamation/list.html.twig', [
            'reclamations' => $reclamationsEnCours,
            'totalReclamations' => $totalReclamations,
            'reclamationsPriorite' => $reclamationsPriorite,
            'reclamationsCeMois' => $reclamationsCeMois,
        ]);
    }
    #[Route('/allreclamation', name: 'app_reclamationback2')]
    public function index3(ReclamationRepository $rp): Response
    {
        $recl = $rp->findAll();
        return $this->render('reclamation/liste2.html.twig', [
            'reclamations' => $recl,
        ]);
    }
    #[Route('/repondre/{id}', name: 'repondre')]
    public function repondre(Request $request, MailerInterface $mailer,int $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        $reclamation = $reclamationRepository->find($id);

        if ($reclamation) {
            $reponse = new Reponse();
            $reponse->setReclamation($reclamation);
            $form = $this->createForm(RepondreType::class, $reponse);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reclamation->setStatus('résolu');

                $entityManager->persist($reponse);
                $entityManager->flush();
                $this->sendConfirmationEmail($reclamation, $mailer);

                $this->addFlash('success', 'La réclamation a été mise à jour avec succès.');
                return $this->redirectToRoute('app_reclamationback');
            }
        }
        return $this->render('reclamation/repondre.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $reclamation,
        ]);
    }
    #[Route('/fermer/{id}', name: 'fermer')]
    public function fermer(int $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        $reclamation = $reclamationRepository->find($id);

        if ($reclamation) {
            $reclamation->setStatus('fermé');
            $entityManager->flush();

            $this->addFlash('success', 'La réclamation a été fermée.');
        } else {
            $this->addFlash('error', 'Réclamation non trouvée.');
        }

        return $this->redirectToRoute('app_reclamationback');
    }
    /**
     * Filtrer les badwords dans une chaîne de texte.
     */
    private function filterBadwords(string $text): string
    {
        $badwordsList = ["badword1", "badword2", "badword3"];

        foreach ($badwordsList as $word) {
            $text = str_ireplace($word, '*' . str_repeat('*', mb_strlen($word) - 1), $text);
        }

        return $text;
    }

    #[Route('/addreclamation', name: 'add_reclamation')]
    public function add(Request $request, UserRepository $userRepository, FlashBagInterface $flashBag): Response
    {
        $recl = new Reclamation();
        $user = $userRepository->find(1);
        $recl->setUser($user);
        $recl->setCreatedDate(new \DateTime());
        $recl->setStatus('en cours');

        $form = $this->createForm(ReclamationType::class, $recl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form['image']->getData();

            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('images_directory'), $fileName);
                $recl->setImage($fileName);
            }

            
            $description = $recl->getDescription();
            $filteredDescription = $this->filterBadwords($description);
            $recl->setDescription($filteredDescription);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recl);
            $entityManager->flush();

            $flashBag->add('success', 'Votre réclamation a été ajoutée avec succès.');

            return $this->redirectToRoute('app_reclamation');
        }
        

        return $this->render('reclamation/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/repondreback', name: 'app_repondreback')]
    public function indexrepondre(ReponseRepository $rp): Response
    {

        $recl = $rp->findAll();
        return $this->render('reclamation/indexreponse.html.twig', [
            'reponse' => $recl,
        ]);
    }
}
