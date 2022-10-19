<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
	#[ Route( '/', name: 'homepage', methods: ['GET', 'HEAD'] ) ]
    public function index( PostRepository $postRepository ) : ?Response
    {
		$posts = $postRepository -> getPosts();
        return $this -> render( 'posts/index.html.twig',
		[
            'posts' => $posts,
			'next_offset' => min( count( $posts ), PostRepository::PAGE_COUNT ),
			'current_page' => 1,
        ]);
    }

	#[ Route( '/post/list/{offset}', name: 'posts_list', methods: ['GET', 'HEAD'] ) ]
	public function list( int $offset, PostRepository $postRepository ) : ?Response
	{
		$posts = $postRepository->getPosts( $offset );

		return $this->render(
			'posts/list.html.twig',
			[
				'posts' => $posts,
				'prev_offset' => $offset - PostRepository::PAGE_COUNT,
				'next_offset' => min( count( $posts ), $offset + PostRepository::PAGE_COUNT ),
				'current_page' => ceil( $offset / PostRepository::PAGE_COUNT ) + 1,
			]
		);
	}

	#[ Route( '/{id}-{tag}', name: 'tag', methods: ['GET', 'HEAD'] ) ]
	public function index_tags( int $id, string $tag, PostRepository $postRepository ) : ?Response
	{
		$posts = $postRepository -> getTagPosts( $id );
		return $this -> render( 'posts/index.html.twig',
		[
			'posts' => $posts,
		]);
	}

	#[ Route( '/post/{id}-{alias}', name: 'post_view', methods: ['GET', 'HEAD'] ) ]
	public function view( int $id, string $alias, PostRepository $postRepository ) : ?Response
	{
		try
		{
			if( !$post = $postRepository -> getPost( $id ) )
			{
				throw new \Exception( 'Error 404. Page not found' );
			}
			$random_posts = $postRepository -> getRandomPosts();

			if( $post->getAlias() !== $alias )
			{
				return $this -> redirectToRoute(
					'post_view',
					[
						'id'    => $post->getId(),
						'alias' => $post->getAlias(),
					],
					301
				);
			}

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
