<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\ReponseReclamation;
use App\Form\ReclamationType;
use App\Form\RepondreType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseReclamationRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class ReclamationController extends AbstractController
{
    private function sendConfirmationEmail($reclamation, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from(new Address('habibbibani79@gmail.com', 'Pidev'))
            ->to($reclamation->getUser()->getEmail())
            ->subject('Reclamation')
            ->html($this->renderView(
                'front/reclamation/email.html.twig',
                ['reclamation' => $reclamation]
            ));

        $mailer->send($email);
    }

    #[Route('/reclamation/pdf/{id}', name: 'reclamationpdf')]
    public function generatePDF($id, ReclamationRepository $repository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $reclamation = $repository->find($id);
        $pdfContent = $this->renderView('front/reclamation/pdf.html.twig', [
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $recl = $rp->findBy(['user' => $this->getUser()->getId()]);
        return $this->render('front/reclamation/index.html.twig', [
            'reclamations' => $recl,
        ]);
    }

    #[Route('/reclamation/edit/{id}', name: 'updaterecla')]
    public function edit(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

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
                $rec->setImage($originalImage);
            }

            $entityManager->persist($rec);
            $entityManager->flush();

            $this->addFlash('success', 'Reclamation mise à jour avec succès.');
            return $this->redirectToRoute('app_reclamation');
        }

        return $this->render('front/reclamation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('reclamation/delete/{id}', name: 'Supprimerrecla')]
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $recl = $entityManager->getRepository(Reclamation::class)->find($id);
        $entityManager->remove($recl);
        $entityManager->flush();
        return $this->redirectToRoute('app_reclamation');
    }

    #[Route('/dashboard/reclamation', name: 'app_reclamationback')]
    public function index2(ReclamationRepository $rp): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

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

        return $this->render('front/reclamation/list.html.twig', [
            'reclamations' => $reclamationsEnCours,
            'totalReclamations' => $totalReclamations,
            'reclamationsPriorite' => $reclamationsPriorite,
            'reclamationsCeMois' => $reclamationsCeMois,
        ]);
    }

    #[Route('/dashboard/allreclamation', name: 'app_reclamationback2')]
    public function index3(ReclamationRepository $rp): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $recl = $rp->findAll();
        return $this->render('front/reclamation/liste2.html.twig', [
            'reclamations' => $recl,
        ]);
    }

    #[Route('/repondre/{id}', name: 'repondre')]
    public function repondre(Request $request, MailerInterface $mailer, int $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $reclamation = $reclamationRepository->find($id);

        if ($reclamation) {
            $reponse = new ReponseReclamation();
            $reponse->setReclamation($reclamation);
            $form = $this->createForm(RepondreType::class, $reponse);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reclamation->setStatut('résolu');

                $entityManager->persist($reponse);
                $entityManager->flush();
                $this->sendConfirmationEmail($reclamation, $mailer);

                $this->addFlash('success', 'La réclamation a été mise à jour avec succès.');
                return $this->redirectToRoute('app_reclamationback');
            }
        }
        return $this->render('front/reclamation/repondre.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/fermer/{id}', name: 'fermer')]
    public function fermer(int $id, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $reclamation = $reclamationRepository->find($id);

        if ($reclamation) {
            $reclamation->setStatut('fermé');
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $badwordsList = ["badword1", "badword2", "badword3"];

        foreach ($badwordsList as $word) {
            $text = str_ireplace($word, '*' . str_repeat('*', mb_strlen($word) - 1), $text);
        }

        return $text;
    }

    #[Route('/addreclamation', name: 'add_reclamation')]
    public function add(Request $request, UserRepository $userRepository, FlashBagInterface $flashBag, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $recl = new Reclamation();
        $user = $this->getUser();
        $recl->setUser($user);
        $recl->setDate(new \DateTime());
        $recl->setStatut('en cours');

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

            $entityManager->persist($recl);
            $entityManager->flush();

            $flashBag->add('success', 'Votre réclamation a été ajoutée avec succès.');

            return $this->redirectToRoute('app_reclamation');
        }


        return $this->render('front/reclamation/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/repondreback', name: 'app_repondreback')]
    public function indexrepondre(ReponseReclamationRepository $rp): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }


        $recl = $rp->findAll();
        return $this->render('front/reclamation/indexreponse.html.twig', [
            'reponse' => $recl,
        ]);
    }
}
