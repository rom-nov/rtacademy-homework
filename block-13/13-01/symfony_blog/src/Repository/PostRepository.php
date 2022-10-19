<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
	public const PAGE_COUNT = 12;
	public const PAGE_RANDOM = 3;
	public const POST_STATUS = 'published';
//	protected int $count_post = 0;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
//		$this -> count_post = $this -> count(
//		[
//			'status' => 'published'
//		]);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function getPosts( int $offset = 0 ) : ?array
	{
		$query =  $this -> createQueryBuilder( 'post' )
			-> where( 'post.status = :val' )
			-> setParameter( 'val', self::POST_STATUS )
			-> orderBy( 'post.id', 'ASC' )
			-> setMaxResults( self::PAGE_COUNT )
			->setFirstResult( $offset )
			-> getQuery();

		return $query -> execute();
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
		$count_post = $this -> count(
		[
			'status' => self::POST_STATUS
		]);
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

	public function getTagPosts( int $id ) : ?array
	{
		$query =  $this -> createQueryBuilder( 'post' )
			-> where( 'post.status = :val', 'post.category = :id' )
			-> setParameter( 'val', self::POST_STATUS )
			-> setParameter( 'id', $id )
			-> orderBy( 'post.id', 'ASC' )
			-> setMaxResults( self::PAGE_COUNT )
			-> getQuery();

		return $query -> execute();
	}
}
