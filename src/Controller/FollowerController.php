<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FollowerController extends AbstractController
{
    #[Route('/follow/{id}', name: 'app_follow')]
    public function follow(User $userToFollow, ManagerRegistry $doctrine, Request $request): Response
    {
        $currentUser = $this->getUser();

        if($userToFollow !== $currentUser) {
            $currentUser->follow($userToFollow);
            $doctrine->getManager()->flush();

        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{id}', name: 'app_unfollow')]
    public function unfollow(User $userToUnfollow,ManagerRegistry $doctrine, Request $request): Response
    {
        $currentUser = $this->getUser();

        if($userToUnfollow !== $currentUser) {
            $currentUser->unfollow($userToUnfollow);
            $doctrine->getManager()->flush();

        }

        return $this->redirect($request->headers->get('referer'));
    }

}
