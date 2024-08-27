<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionVoter extends Voter
{
    public const ADD = 'QUESTION_ADD';
    public const DELETE = 'QUESTION_DELETE';
    public const EDIT = 'QUESTION_EDIT';

    public function __construct(
        private Security $security
    )
    {
        
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        // Si l'attribut correspond à la valeur de la constante "ADD", on applique
        // les règles du voter
        if ($attribute === self::ADD) {
            return true;
        }

        return in_array($attribute, [self::DELETE, self::EDIT])
            && $subject instanceof \App\Entity\Question;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ADD:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->security->isGranted('ROLE_USER');
                break;
            case self::EDIT:
                return $subject->getUser() === $user;
                break;
            case self::DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                return $subject->getUser() === $user || $this->security->isGranted('ROLE_ADMIN');
                break;
        }

        return false;
    }
}
