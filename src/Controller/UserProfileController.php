<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_user_profile')]
    public function show(User $user): Response
    {
        return $this->render('user_profile/show.html.twig', [
            'user' => $user,
        ]);
    }
}
