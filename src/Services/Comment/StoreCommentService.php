<?php

namespace App\Services\Comment;
use Symfony\Component\HttpFoundation\Response;

class StoreCommentService
{
    
    public function store($entityManagerInterface, $comment, $userRepository, $post): bool
    {
        $user = $userRepository->find(1);
        $comment->setUser($user);
        $comment->setPost($post);
        $entityManagerInterface->persist($comment);
        $entityManagerInterface->flush();

        return true;
    }

}
