<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PermissionVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['VIEW', 'EDIT']) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (!$subject instanceof User) {
            return false;
        }

        $currentUser = $token->getUser();

        switch ($attribute) {
            case 'VIEW':
                return $currentUser === $subject;
            case 'EDIT':
                return $currentUser->getRoles() && in_array('ROLE_ADMIN', $currentUser->getRoles());
            default:
                return false;
        }
    }
}
