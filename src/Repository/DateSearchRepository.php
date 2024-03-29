<?php

namespace App\Repository;

use App\Entity\DateSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DateSearch>
 *
 * @method DateSearch|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateSearch|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateSearch[]    findAll()
 * @method DateSearch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateSearchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateSearch::class);
    }

//    /**
//     * @return DateSearch[] Returns an array of DateSearch objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DateSearch
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
