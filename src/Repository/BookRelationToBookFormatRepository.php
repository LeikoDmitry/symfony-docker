<?php

namespace App\Repository;

use App\Entity\BookRelationToBookFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookRelationToBookFormat>
 *
 * @method BookRelationToBookFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookRelationToBookFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookRelationToBookFormat[]    findAll()
 * @method BookRelationToBookFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRelationToBookFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookRelationToBookFormat::class);
    }
}
