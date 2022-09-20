<?php

namespace App\Security;

use App\Entity\Hackaton;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class HackatonVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        $supportsAttribute = in_array($attribute, [self::VIEW, self::EDIT, self::DELETE]);
        $supportsSubject = $subject instanceof Hackaton;

        dump($supportsAttribute, $supportsSubject);
        //return $supportsAttribute && $supportsSubject;
        return true;
    }

    /**
     * @param string $attribute
     * @param Hackaton $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canViewHackaton($subject, $user);
            case self::DELETE:
                return $this->canDeleteHackaton($subject, $user);
            case self::EDIT:
                return $this->canEditHackaton($subject, $user);
        }
    }

    private function canDeleteHackaton(Hackaton $hackaton, User $user): bool
    {
        return $user->getRoles() === ['ROLE_ADMIN'] || $hackaton->getOwner() === $user;
    }

    private function canViewHackaton(Hackaton $hackaton, User $user): bool
    {
        return $user->getRoles() === ['ROLE_COACH'] || $user->getRoles() === ['ROLE_ADMIN'] || $hackaton->getOwner() === $user;
    }

    private function canEditHackaton(Hackaton $hackaton, User $user): bool
    {
        return $user->getRoles() === ['ROLE_COACH'] || $user->getRoles() === ['ROLE_ADMIN'] || $hackaton->getOwner() === $user;
    }
}
