<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Citoyen;
use Endroid\QrCode\QrCode;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Endroid\QrCode\Writer\PngWriter;
use App\Repository\CitoyenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    #[Route('/test-qr-code', name: 'test_qr_code')]
    public function testQrCode(): Response
    {
        // Créez une instance de QrCode
        $qrCode = new QrCode('Contenu du QR Code');

        // Créez une instance de writer
        $writer = new PngWriter();

        // Écrivez le code QR en tant que Data URI
        $qrCodeDataUri = $writer->write($qrCode)->getDataUri();

        // Passez le QR code à la vue
        return $this->render('back/citoyen/test_qr_code.html.twig', [
            'qrCodeDataUri' => $qrCodeDataUri
        ]);
    }

    #[Route('/cin/submit', name: 'citoyen_cin_submit', methods: ['POST'])]
    public function submitCin(Request $request, CitoyenRepository $citoyenRepository, SessionInterface $session): Response
    {
        $cin = $request->request->get('cin');
        $citoyen = $citoyenRepository->findOneBy(['cin' => $cin]);

        if ($citoyen) {
            $session->set('Citoyen', $citoyen);

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
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher, SessionInterface $session,  CitoyenRepository $citoyenRepository): Response
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
            $citoyenId = $session->get('Citoyen');

            if ($citoyenId) {
                $citoyen = $citoyenRepository->find($citoyenId);
                if ($citoyen) {
                    $user->setCitoyen($citoyen); // Associez l'entité Citoyen à l'entité User
                } else {
                    $this->addFlash('error', 'Le citoyen n\'est plus disponible.');
                    return $this->redirectToRoute('citoyen_cin'); // Ou toute autre redirection appropriée
                }
            }
            $user->setRole("user");


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

    #[Route('/stats', name: 'user_citoyen_stats')]
    public function stats(EntityManagerInterface $entityManager): JsonResponse
    {
        $userRepository = $entityManager->getRepository(User::class);
        $citoyenRepository = $entityManager->getRepository(Citoyen::class);

        // Collecte des données
        $userStats = $userRepository->countByDate();
        $citoyenStats = $citoyenRepository->countByDate();

        return $this->json([
            'userStats' => $userStats,
            'citoyenStats' => $citoyenStats
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
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Supposons que vous avez un champ 'plainPassword' dans votre formulaire
            $plaintextPassword = $form->get('password')->getData();
            if ($plaintextPassword) {
                // Hash le nouveau mot de passe et l'enregistre
                $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
                $user->setPassword($hashedPassword);
            }

            // Faites les autres mises à jour nécessaires sur l'entité utilisateur ici

            $entityManager->flush();
            $this->addFlash('success', 'User updated successfully.');
            return $this->redirectToRoute('app_citoyen_index');
        }

        return $this->renderForm('front/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit2', name: 'app_user_edit2', methods: ['GET', 'POST'])]
    public function edit2(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plaintextPassword = $form->get('password')->getData();
            if ($plaintextPassword) {
                // Hash le nouveau mot de passe et l'enregistre
                $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
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

        return $this->redirectToRoute('app_citoyen_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_user_delete2', methods: ['POST'])]
    public function delete2(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }
}
