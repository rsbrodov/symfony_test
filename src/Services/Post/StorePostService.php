<?php

namespace App\Services\Post;
use Symfony\Component\HttpFoundation\Response;

class StorePostService
{
    
    public function store($entityManagerInterface, $post): bool
    {
        $entityManagerInterface->persist($post);
        $entityManagerInterface->flush();

        return true;
    }

}
