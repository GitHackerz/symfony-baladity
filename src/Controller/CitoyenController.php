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

#[Route('/citoyen')]
class CitoyenController extends AbstractController
{
    #[Route('/', name: 'app_citoyen_index', methods: ['GET'])]
    public function index(CitoyenRepository $citoyenRepository, UserRepository $userRepository): Response
    {
        $maleCount = $citoyenRepository->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.genre = :male')
            ->setParameter('male', 'Homme')
            ->getQuery()
            ->getSingleScalarResult();

        $femaleCount = $citoyenRepository->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.genre = :female')
            ->setParameter('female', 'Femme')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('back/citoyen/index.html.twig', [
            'citoyens' => $citoyenRepository->findAll(),
            'users' => $userRepository->findAll(),
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
        ]);
    }
    #[Route('/gender-stats', name: 'citoyen_gender_stats')]
    public function genderStats(CitoyenRepository $citoyenRepository): Response
    {
        $maleCount = $citoyenRepository->count(['genre' => 'Homme']);
        $femaleCount = $citoyenRepository->count(['genre' => 'Femme']);

        return $this->render('back/citoyen/gender_stats.html.twig', [
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
        ]);
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
    public function delete(Request $request, Citoyen $citoyen, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $citoyen->getId(), $request->request->get('_token'))) {
            $entityManager->remove($citoyen);
            $entityManager->flush();
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
    #[Route('/citoyen/recherche', name: 'citoyen_recherche', methods: ['GET'])]
    public function recherche(Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('q');
        $citoyens = $entityManager->getRepository(Citoyen::class)->findByQuery($query);

        return $this->render('back/citoyen/tableContent.html.twig', [
            'citoyens' => $citoyens,
        ]);
    }
}
