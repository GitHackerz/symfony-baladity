<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\CitoyenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('front/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/cin', name: 'citoyen_cin', methods: ['GET'])]
    public function enterCin(): Response
    {
        return $this->render('front/user/_enter_cin.html.twig');
    }
    #[Route('/cin/submit', name: 'citoyen_cin_submit', methods: ['POST'])]
    public function submitCin(Request $request, CitoyenRepository $citoyenRepository): Response
    {
        $cin = $request->request->get('cin');
        $citoyen = $citoyenRepository->findOneBy(['cin' => $cin]);

        if ($citoyen) {
            return $this->render('front/user/show_citoyen.html.twig', ['citoyen' => $citoyen]);
        } else {
            $this->addFlash('error', 'CIN not found. Please try again.');
            return $this->redirectToRoute('citoyen_cin'); // Redirects back to the form
        }
    }


    #[Route('/login', name: 'login', methods: ['GET'])]
    public function index2(UserRepository $userRepository): Response
    {
        return $this->render('front/user/login.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/auth', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $plaintextPassword = $form->get('password')->getData(); // Assuming the form field is named 'password'
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            // Handle the image upload, if any
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $user->setImage($newFilename);
            }

            // Persist and flush the user entity
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect after successful registration
            $this->addFlash('success', 'User registered successfully.');
            return $this->redirectToRoute('app_login'); // Adjust the route name if necessary
        }

        // Render the form
        return $this->renderForm('front/user/inscription.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('front/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit2', name: 'app_user_edit2', methods: ['GET', 'POST'])]
    public function edit2(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/user/_editcurrentuser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
