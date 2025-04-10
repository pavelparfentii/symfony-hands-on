<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Image;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts, EntityManagerInterface $entityManager): Response
    {
        // Create new entity
//        $microPost = new MicroPost();
//        $microPost->setTitle('Title');
//        $microPost->setText('Text');
//        $microPost->setCreated(new \DateTime('now'));
//        $entityManager->persist($microPost);
//        $entityManager->flush();

        //Update entity
//        $microPost = $posts->find(4);
//        $microPost->setTitle('TTTT');
//        $entityManager->flush();

        //Remove entity
//        $microPost = $posts->find(4);
//        $entityManager->remove($microPost);
//        $entityManager->flush();
        $microPosts = $posts->findAllComments();

//        dd($posts->findOneBy(['title'=>'Micropost 1']));
        return $this->render('micro_post/index.html.twig', [
            'posts' => $microPosts,
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    #[IsGranted(MicroPost::VIEW, 'post')]
    public function show(MicroPost $post):Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]

    #[IsGranted('ROLE_WRITER')]
    public function add(Request $request, EntityManagerInterface $entityManager):Response
    {
//        $microPost = new MicroPost();
//        $form = $this->createFormBuilder($microPost)
//            ->add('title')
//            ->add('text')
//            ->add('submit', SubmitType::class, ['label' => 'Create Micro Post'])
//            ->getForm();

        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $microPost = $form->getData();
            $microPost->setCreated(new \DateTime('now'));
            $microPost->setAuthor($this->getUser());

            $entityManager->persist($microPost);
            $entityManager->flush();

            //flash message
            $this->addFlash('success', 'Your Post created!');

            //redirect to route
            return $this->redirectToRoute('app_micro_post');
        }



        return $this->render('micro_post/add.html.twig', [
           'form'=>$form
        ]);
    }

    #[Route('//micro-post/{post}/edit', name: 'app_micro_post_edit')]
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $entityManager):Response
    {
//        $form = $this->createFormBuilder($post)
//            ->add('title')
//            ->add('text')
//            ->getForm();
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        $this->denyAccessUnlessGranted('EDIT', $post);

        if($form->isSubmitted() && $form->isValid()) {
            $microPost = $form->getData();

            $entityManager->flush();

            //flash message
            $this->addFlash('success', 'Your Post updated!');

            //redirect to route
            return $this->redirectToRoute('app_micro_post');
        }
        

        return $this->render('micro_post/edit.html.twig', [
            'form'=>$form,
            'post'=>$post
        ]);
    }

    #[Route('//micro-post/{post}/comment', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $post, Request $request, EntityManagerInterface $entityManager):Response
    {

        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            //flash message
            $this->addFlash('success', 'Your Post updated!');

            //redirect to route
            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]);
        }


        return $this->render('micro_post/comment.html.twig', [
            'form'=>$form,
            'post'=>$post
        ]);
    }
}
