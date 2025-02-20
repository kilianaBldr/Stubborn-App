<?php

namespace App\Repository;

use App\Entity\Sweatshirt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sweatshirt>
 */
class SweatshirtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sweatshirt::class);
    }

    public function findFeaturedSweatshirts(int $limit = 3): array
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.isFeatured = :featured')
        ->setParameter('featured', true)
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
    }

//    public function findOneBySomeField($value): ?Sweatshirt
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
