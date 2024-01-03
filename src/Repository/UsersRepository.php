<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findIdByUsername(string $username)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $username)
            ->getQuery()
            ->getSingleResult()
            ;
    }

    public function findByPatient($user)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.appointments', 'a')
            ->addSelect('a')
            ->innerJoin('a.schedule', 's')
            ->addSelect('s')
            ->andWhere('u.username = :val')
            ->setParameter('val',$user)
            //->setFirstResult(1)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findUniqueScheduleByPatient($user)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.appointments', 'a')
            ->addSelect('a')
            ->innerJoin('a.schedule', 's')
            ->addSelect('s')
            ->andWhere('u.username = :val')
            ->setParameter('val',$user)
            // ->setFirstResult(1)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findName($user)
    {
        return $this->createQueryBuilder('u')
            ->select('u.patientNom,u.patientPrenom')
            ->andWhere('u.username = :val')
            ->setParameter('val',$user)
            ->getQuery()
            ->getSingleResult()
            ;
    }
    public function updateAppointment(mixed $userId, mixed $scheduleId,$symptome,$comments ): void
    {

        $query = $this->createQueryBuilder('')
            ->update(Appointment::class, 'a')

            ->set('a.Symptome', ':Symptome')
            ->setParameter('Symptome', $symptome)

            ->set('a.Comments', ':Comments')
            ->setParameter('Comments', $comments)

            ->where('a.users =:userId')
            ->setParameter('userId', $userId)

            ->andWhere('a.schedule = :schedule')
            ->setParameter('schedule',$scheduleId)

            ->getQuery();
        $query->execute();
    }

    public function updateProfil(
        mixed $patientFirstName, mixed $patientLastName,
        mixed $patientMaritialStatus, mixed $patientDateDeNaissance,mixed $username
    ): void
    {
        $query = $this->createQueryBuilder('')
            ->update(Users::class, 'u')

            ->set('u.patientNom', ':patientNom')
            ->setParameter('patientNom', $patientFirstName)

            ->set('u.patientPrenom', ':patientPrenom')
            ->setParameter('patientPrenom', $patientLastName)

            ->set('u.patientMaritialStatus', ':patientMaritialStatus')
            ->setParameter('patientMaritialStatus', $patientMaritialStatus)

            ->set('u.patientDateDeNaissance', ':patientDateDeNaissance')
            ->setParameter('patientDateDeNaissance', $patientDateDeNaissance)


            ->where('u.username =:username')
            ->setParameter('username', $username)


            ->getQuery();
        $query->execute();
    }

    public function findIfNotExistOtherRDv(string $userEncours)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.appointments', 'a')
            ->innerJoin('a.schedule', 's')
            ->addSelect('COUNT(a.id)')
            ->andWhere('u.username = :val')
            ->setParameter('val',$userEncours)
            ->getQuery()
            ->getScalarResult()
            ;
    }

//    /**
//     * @return Users[] Returns an array of Users objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Users
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
