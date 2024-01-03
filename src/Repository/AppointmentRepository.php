<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function findAppointmentBySchedule($schedule)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.schedule = :val')
            ->setParameter('val', $schedule)
            ->getQuery()
            ->getResult()
            ;

    }

    public function findAppointmentById(int $id)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.users', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult()
            ;
    }

    public function trouvelappointment(int $id)
    {
        $query = $this->createQueryBuilder('a')
            ->andWhere('a.schedule = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
        $query->execute();
    }

//    /**
//     * @return Appointment[] Returns an array of Appointment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Appointment
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}