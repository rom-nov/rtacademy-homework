<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
	public const PAGE_COUNT = 4;
	public const PAGE_RANDOM = 3;
	public const POST_STATUS = 'published';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function save( Post $entity, bool $flush = false ) : void
    {
        $this -> getEntityManager() -> persist($entity);

        if ( $flush )
		{
            $this->getEntityManager() -> flush();
        }
    }

    public function remove( Post $entity, bool $flush = false ) : void
    {
        $this -> getEntityManager() -> remove( $entity );

        if ( $flush )
		{
            $this -> getEntityManager() -> flush();
        }
    }

	public function getCountPosts( int $id = -1 ) : int
	{
		if( $id >= 0 )
		{
			return $this -> count(
			[
				'status' => self::POST_STATUS,
				'id' => $id,
			]);
		}
		return $this -> count(
		[
			'status' => self::POST_STATUS
		]);
	}

	public function getPosts( int $offset = 0 ) : ?array //Paginator
	{
		$query =  $this -> createQueryBuilder( 'post' )
			-> where( 'post.status = :val' )
			-> setParameter( 'val', self::POST_STATUS )
			-> orderBy( 'post.id', 'ASC' )
			-> setMaxResults( self::PAGE_COUNT )
			-> setFirstResult( $offset )
			-> getQuery();

		return $query -> execute();
		//return new Paginator( $query );
	}

	public function getPost( int $id ) : ?Post
	{
		return $this -> findOneBy(
			[
				'id' => $id,
				'status' => self::POST_STATUS
			]);
	}

	public function getRandomPosts() : ?array
	{
		$count_post = $this -> getCountPosts();
		return $this -> findBy(
			[
				'status' => self::POST_STATUS
	    	],
			[
				'id' => 'ASC'
			],
			self::PAGE_RANDOM, rand(1, $count_post - self::PAGE_RANDOM )
		);
	}

	public function getCategoryPosts( int $id, int $offset = 0 ) : ?array
	{
		$query =  $this -> createQueryBuilder( 'post' )
			-> where( 'post.status = :val', 'post.category = :id' )
			-> setParameter( 'val', self::POST_STATUS )
			-> setParameter( 'id', $id )
			-> orderBy( 'post.id', 'ASC' )
			-> setMaxResults( self::PAGE_COUNT )
			-> setFirstResult( $offset )
			-> getQuery();

		return $query -> execute();
	}
}
