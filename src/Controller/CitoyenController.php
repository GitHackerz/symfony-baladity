<?php

namespace App\Controller;

use App\Entity\Citoyen;
use App\Form\CitoyenType;
use App\Repository\UserRepository;
use App\Repository\CitoyenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/dashboard/citoyen')]
class CitoyenController extends AbstractController
{
    #[Route('', name: 'app_citoyen_index', methods: ['GET'])]
    public function index(CitoyenRepository $citoyenRepository, UserRepository $userRepository): Response
    {
        $maleCount = $citoyenRepository->count(['genre' => 'Homme']);
        $femaleCount = $citoyenRepository->count(['genre' => 'Femme']);

        return $this->render('back/citoyen/index.html.twig', [
            'citoyens' => $citoyenRepository->findAll(),
            'users' => $userRepository->findAll(),
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
        ]);
    }

    #[Route('/profile', name: 'back_profile')]
    public function profile(): Response
    {
        return $this->render('back/citoyen/profile.html.twig', []);
    }

    #[Route('/new', name: 'app_citoyen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $citoyen = new Citoyen();
        $form = $this->createForm(CitoyenType::class, $citoyen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($citoyen);
            $entityManager->flush();

            return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/citoyen/new.html.twig', [
            'citoyen' => $citoyen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citoyen_show', methods: ['GET'])]
    public function show(Citoyen $citoyen): Response
    {
        return $this->render('back/citoyen/show.html.twig', [
            'citoyen' => $citoyen,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_citoyen_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Citoyen $citoyen, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CitoyenType::class, $citoyen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/citoyen/edit.html.twig', [
            'citoyen' => $citoyen,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citoyen_delete', methods: ['POST'])]
    public function delete(Request $request, Citoyen $citoyen, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $citoyen->getId(), $submittedToken)) {
            $user = $userRepository->findOneBy(['citoyen' => $citoyen]);

            if ($user) {
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash('warning', 'L\'utilisateur associé au citoyen a été supprimé.');
            }

            $entityManager->remove($citoyen);
            $entityManager->flush();
            $this->addFlash('success', 'Le citoyen a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Le jeton CSRF n\'est pas valide.');
        }

        return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'x', methods: ['POST'])]
    public function delete2(Request $request, Citoyen $citoyen, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $submittedToken = $request->request->get('_token');

        if ($csrfTokenManager->isTokenValid(new CsrfToken('delete' . $citoyen->getId(), $submittedToken))) {
            // Perform the deletion
            $entityManager->remove($citoyen);
            $entityManager->flush();

            // Fetch the updated list of citoyens
            $updatedCitoyensList = $entityManager->getRepository(Citoyen::class)->findAll();

            // Render the same page with the updated list
            return $this->render('back/citoyen/index.html.twig', [
                'citoyens' => $updatedCitoyensList,
            ]);
        }

        // If the token is invalid, throw an exception or handle it as required
        throw $this->createAccessDeniedException('Invalid CSRF token.');
    }

    #[Route('/recherche', name: 'citoyen_recherche', methods: ['GET'])]
    public function recherche(Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('q');
        $citoyens = $entityManager->getRepository(Citoyen::class)->findByQuery($query);

        return $this->render('back/citoyen/tableContent.html.twig', [
            'citoyens' => $citoyens,
        ]);
    }
}
