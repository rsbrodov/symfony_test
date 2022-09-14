<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentFormType;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Services\Comment\StoreCommentService;
use App\Services\Post\StorePostService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class PostController extends AbstractController
{
    public $storePostService;
    public $storeCommentService;

    public function __construct(StorePostService $storePostService, StoreCommentService $storeCommentService)
    {
        $this->storePostService = $storePostService;
        $this->storeCommentService = $storeCommentService;
    }

    /**
     * @Route("/post", name="app_post")
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/store", name="post_store")
     */
    /*public function store(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $post = new Post();
        $post->setTitle('title'.rand(1,100));
        $post->setDescription('description'.rand(1,100));
        $entityManager->persist($post);
        $entityManager->flush();
        return new Response('Saved new data with id '.$post->getId());
    }*/
    /**
     * @Route("/post/create", name="post_create")
     */
    
    public function create(Environment $twig, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $post = new Post;
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->storePostService->store($entityManagerInterface, $post);
            $this->redirectToRoute('app_post');
        }
        return new Response($twig->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function show(int $id, PostRepository $postRepository, Request $request, EntityManagerInterface $entityManagerInterface, UserRepository $userRepository): Response
    {
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $comment = new Comment;

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->storeCommentService->store($entityManagerInterface, $comment, $userRepository, $post);
            $this->redirectToRoute('post_show', ['id' => $id]);
        }
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
