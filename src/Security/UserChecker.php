<?php

namespace App\Security;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface{

    /**
     * @var \App\Entity\User $user
    */
    public function checkPreAuth(UserInterface $user): void
    {
        if($user->getBannedUntil() === null){
            return;
        }

        $now = new \DateTime();

        if($now < $user->getBannedUntil()){
            throw new AccessDeniedException('This user is banned.');
        }
    }
    public function checkPostAuth(UserInterface $user): void
    {}
}