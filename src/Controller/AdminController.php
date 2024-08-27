<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin')]

class AdminController extends AbstractController
{
    #[Route('', name: '')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['nom' => 'ASC']);

        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/user/{id}/role', name: '_change_role')]
    public function roleAdmin(User $user, EntityManagerInterface $entityManager): RedirectResponse {

        $user->setRoles(['ROLE_ADMIN']);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', "L'utilisateur {$user->getNom()} est désormais administrateur");

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/reporting/questions', name: '_reporting_question')]
    public function reportingQuestions(QuestionRepository $questionRepository): Response {

        $questions = $questionRepository->getQuestionSixReponses();
        
        return $this->render('admin/reporting/question.html.twig', [
            'questions' => $questions
        ]);
    }

    #[Route('/reporting/reponses', name: '_reporting_response')]
    public function reportingReponse(ReponseRepository $reponseRepository): Response {

        $reponses = $reponseRepository->getReponseTenVote();
        
        return $this->render('admin/reporting/reponse.html.twig', [
            'reponses' => $reponses
        ]);
    }

}
