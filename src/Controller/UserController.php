<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[IsGranted('USER_ACCESS', null, 'Veuillez vous connecter pour accéder à cette partie')]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {

    }
        
    // Gestion du profil utilisateur
    #[Route('/user/profile', name: 'app_user_profile')]
    public function profile(Request $request, UploadService $uploadService): Response
    {  //$this->getUser();
        /** @var User $user */
        $user = $this->getUser(); // Correspond à l'utilisateur connecté
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'is_profile' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Recuperation de l'image
            $avatarFile = $form->get('avatarFile')->getData();

            // Si une image a été soumise, on traite celle-ci
            if ($avatarFile) {
                $fileName = $uploadService->upload($avatarFile, $user->getAvatar());
                $user->setAvatar($fileName);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Vos modifications ont bien été prises en compte');
        }

        return $this->render('user/profile.html.twig', [
            'formProfile' => $form,
        ]);
    }

    #[Route('/user/delete/{id?}', name: 'app_user_delete')]
    public function delete(?User $user, Request $request): RedirectResponse
    {
        // Récupération du jeton CSRF du formulaire
        $token = $request->request->get('_token');
        $method = $request->request->get('_method');

        if ($method === 'DELETE' && $this->isValidCSRFToken($user, $token)) {
            /** @var User $user */
            $user = $user ?? $this->getUser();

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            // Supprime l'image de l'avatar utilisateur sur le serveur
            $filesystem = new Filesystem();
            if ($user->getAvatar() !== 'imgs/user_default.webp') {
                $filesystem->remove($user->getAvatar());
            }

            // Redirection
            return $this->redirectIsAdmin($user, $request);
        }

        // Retour vers la page de profil si le token CSRF est invalide
        $this->addFlash('error', 'Jeton CSRF invalide');

        return $this->redirectToRoute('app_user_profile');
    }

    /**
     * Vérifie un jeton lors de la suppression utilisateur
     */
    private function isValidCSRFToken(?User $user, string $token): bool
    {
        // Vérifier le jeton CSRF selon si l'on est un admin ou pas
        if ($user !== null && $this->isGranted('ROLE_ADMIN')) {
            return $this->isCsrfTokenValid('delete_user-'. $user->getId(), $token);
        }

        // Vérification si un utilisateur supprime son propre compte
        return $this->isCsrfTokenValid('delete_user', $token);
    }

    /**
     * Redirige l'utilisateur après une suppression de compte selon s'il est un
     * administrateur ou simple utilisateur
     */
    private function redirectIsAdmin(User $user, Request $request): RedirectResponse
    {
        $userConnected = $this->getUser();

        // Redirection pour un administrateur
        if ($user !== $userConnected && $this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('success', 'Le compte utilisateur a été supprimé');
            return $this->redirectToRoute('app_admin');
        }

        // Invalidation de la session utilisateur
        $session = $request->getSession();
        $session->invalidate();

        // Annule le token de sécurité utilisateur qui était lié à la session de connexion
        $this->container->get('security.token_storage')->setToken(null);

        $this->addFlash('success', 'Votre compte est désormais supprimé !');

        return $this->redirectToRoute('app_home');
    }

}
