<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfileController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/complet-profile', name: 'app_complet_profile', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('profile/complet_profile.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    // Traiter la soumission du formulaire
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/complet-profile', name: 'app_complet_profile_submit', methods: ['POST'])]
    public function submitForm(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /**
         * @var User
         */
        $user = $this->getUser();

        if ($user->isFirstLogin() === false) {
            return $this->redirectToRoute('app_admin_planning', ['offset' => 0]);
        }

        if ($this->isCsrfTokenValid('complete_profil', $request->request->get('_token'))) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $password = $request->request->get('password');
            $validPassword = $request->request->get('validPassword');

            if (empty($nom) || empty($prenom)) {
                $this->addFlash('error', 'Nom et prénom obligatoires');
                return $this->redirectToRoute('app_complet_profile');
            }

            if (strlen($password) < 8) {
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères');
                return $this->redirectToRoute('app_complet_profile');
            }

            if ($password === $validPassword) {
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $user->setFirstLogin(false);

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_admin_planning', ['offset' => 0]);
            } else {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas');
                return $this->redirectToRoute('app_complet_profile');
            }
        } else {
            return $this->json(['status' => 'error', 'message' => 'Token CSRF invalide']);
        }

    }
}
