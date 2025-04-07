<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    private array $messages =  [
        ['message' => 'Hello', 'created' => '2025/06/12'],
     ['message' => 'Hi', 'created' => '2025/04/12'],
     ['message' => 'Bye!', 'created' => '2023/05/12']];

//    #[Route('/test/{limit<\d+>?3}', name: 'app_test')]
//    public function index(int $limit): Response
//    {
    #[Route('/test', name: 'app_test')]
    public function index(UserProfileRepository $profiles, EntityManagerInterface $entityManager): Response
    {
//        $user = new User();
//        $user->setEmail('email@email.com');
//        $user->setPassword('password');
//
//                $profile = new UserProfile();
//                $profile->setUser($user);
//
//        $entityManager->persist($profile);
//        $entityManager->flush();

        $profile = $profiles->find(1);
        $entityManager->remove($profile);
        $entityManager->flush();


        return $this->render('test/test.html.twig', [
            'messages' =>$this->messages,
            'limit' => 3,
        ]);
//        return new Response(implode(',', array_slice($this->messages, 0, $limit) ));
    }

    #[Route('/message/{id<\d+>}', name: 'app_message')]
    public function showOne($id): Response
    {
        return $this->render('test/index.html.twig', [
            'message' => $this->messages[$id],
        ]);
//        return new Response($this->messages[$id]);
    }
}
