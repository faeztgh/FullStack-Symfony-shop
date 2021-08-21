<?php

namespace App\Security\Voter;

use App\Model\OwnedInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OwnerVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports(string $attribute, $subject): bool
    {

        return $subject instanceof OwnedInterface;
    }

    /**
     * @param string $attribute
     * @param OwnedInterface $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user) {
            return false;
        }

        return $subject->getOwner() == $user;
    }

}
