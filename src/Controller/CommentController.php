<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/store", name="app_comment_store")
     */
    public function storePost(ManagerRegistry $doctrine, PostRepository $postRepository, UserRepository $userRepository): Response
    {
        $entityManager = $doctrine->getManager();

        $post = $postRepository->find(1);
        $user = $userRepository->find(1);
        $comment = new Comment();
        $comment->setUser($user);
        $comment->setPost($post);
        $comment->setText('sfsdfsfsfsdfsdfsd');
        $entityManager->persist($comment);
        $entityManager->flush();
        return new Response('Saved new data with id '.$comment->getId());
    }
    
}
