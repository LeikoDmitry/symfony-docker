<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getBookTotalRatingSum(int $id): int
    {
        return (int) $this->getEntityManager()->createQuery(
            dql: 'SELECT SUM(r.rating) FROM App\Entity\Review r WHERE r.book = :id'
        )->setParameter(key: 'id', value: $id)->getSingleScalarResult();
    }

    public function getPageByBookId(int $id, int $offset, int $limit): Paginator
    {
        $query = $this->getEntityManager()->createQuery(
            dql: 'SELECT r FROM App\Entity\Review r WHERE r.book = :id ORDER BY r.createdAt DESC '
        )->setParameter(key: 'id', value: $id)->setFirstResult($offset)->setMaxResults($limit);

        return new Paginator(query: $query, fetchJoinCollection: false);
    }

    public function countByBook(int $id): int
    {
        return $this->count(['book' => $id]);
    }
}
