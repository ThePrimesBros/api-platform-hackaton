<?php

namespace App\Security;

use App\Entity\Hackaton;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class HackatonVoter extends Voter
{

    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        // only vote on `Post` objects
        if (!$subject instanceof Hackaton) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Hackaton $hackaton */
        $hackaton = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canViewHackaton($hackaton, $user);
            case self::EDIT:
                return $this->canDeleteHackaton($hackaton, $user);
        }
    }

    private function canDeleteHackaton(Hackaton $hackaton, User $user): bool
    {
        return $user->getRoles() === ['ROLE_ADMIN'] || $hackaton->getOwner() === $user;
    }

    private function canViewHackaton(Hackaton $hackaton, User $user): bool
    {
        return $user->getRoles() === ['ROLE_COACH'] || $hackaton->getOwner() === $user;
    }
}
