<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
	#[Route('/', name: 'homepage', methods: ['GET', 'HEAD'])]
    public function index( PostRepository $postRepository ) : ?Response
    {
		$posts = $postRepository -> getPosts();
        return $this -> render('posts/index.html.twig',
		[
            'posts' => $posts,
        ]);
    }

	#[Route('/post/{id}-{alias}', name: 'post_view', methods: ['GET', 'HEAD'])]
	public function view( int $id, string $alias, PostRepository $postRepository ) : ?Response
	{
		try
		{
			if( !$post = $postRepository -> getPost( $id ) )
			{
				throw new \Exception( 'Error 404. Page not found' );
			}
			$random_posts = $postRepository -> getRandomPosts();
			return $this -> render( 'posts/view.html.twig',
			[
				'post' => $post,
				'random_posts' => $random_posts,
			]);
		}
		catch( \Exception $error )
		{
			return $this -> render( 'posts/404.html.twig',
			[
				'error' => $error,
			]);
		}

//		$post = $postRepository -> getPost( $id );
//		$random_posts = $postRepository -> getRandomPosts();
//
//		if( !$post )
//		{
//			throw $this->createNotFoundException( 'Post with #' . $id . ' not found' );
//		}
//
//		if( $post->getAlias() !== $alias )
//		{
//			// на випадок некоректного alias у URI -> переходимо до коректної сторінки
//			return $this->redirectToRoute(
//				'post_view',
//				[
//					'id'    => $post->getId(),
//					'alias' => $post->getAlias(),
//				],
//				301
//			);
//		}
//		return $this -> render( 'posts/view.html.twig',
//		[
//			'post' => $post,
//			'random_posts' => $random_posts,
//		]);
	}
}
