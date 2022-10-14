<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
	#[Route('/', name: 'homepage', methods: ['GET', 'HEAD'])]
    public function index( PostRepository $postRepository ): ?Response
    {
		$posts = $postRepository -> getPosts();
		//dd( $posts );
        return $this->render('posts/index.html.twig',
		[
            'posts' => $posts,
        ]);
    }
}
