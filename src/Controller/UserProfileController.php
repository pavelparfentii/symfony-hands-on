<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_user_profile')]
    public function show(User $user,
                         MicroPostRepository $posts): Response
    {
        return $this->render('user_profile/show.html.twig', [
            'user' => $user,
            'posts'=>$posts->findAllByAuthor($user)
        ]);
    }

    #[Route('/profile/{id}/follows', name: 'app_user_follows')]
    public function follows(User $user): Response
    {
        return $this->render('user_profile/follows.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/{id}/followers', name: 'app_user_followers')]
    public function followers(User $user): Response
    {
        return $this->render('user_profile/follower.html.twig', [
            'user' => $user,
        ]);
    }
}
