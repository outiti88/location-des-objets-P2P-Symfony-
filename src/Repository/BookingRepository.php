<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function getDemandes(User $user)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->innerJoin('b.ad', 'a')
            ->andWhere('a.author = :author')
            ->setParameter('author', $user)
            ->andWhere('b.confirm = :confirm')
            ->setParameter('confirm', false)
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getNotifBooker(User $user)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->innerJoin('b.ad', 'a')
            ->andWhere('b.booker = :booker')
            ->setParameter('booker', $user)
            ->andWhere('b.confirm = :confirm')
            ->setParameter('confirm', true)
            ->andWhere('b.vuNotifClient = :vuNotifClient')
            ->setParameter('vuNotifClient', false)
            ->andWhere('b.endDate < :endDate')
            ->setParameter('endDate', new \DateTime())
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function getNotifAuthor(User $user)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->innerJoin('b.ad', 'a')
            ->andWhere('a.author = :author')
            ->setParameter('author', $user)
            ->andWhere('b.confirm = :confirm')
            ->setParameter('confirm', true)
            ->andWhere('b.vuNotifProp = :vuNotifProp')
            ->setParameter('vuNotifProp', false)
            ->andWhere('b.endDate < :endDate')
            ->setParameter('endDate', new \DateTime())
            ->getQuery()
            ->getResult();
        return $query;
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
