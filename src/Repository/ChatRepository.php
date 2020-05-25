<?php

namespace App\Repository;

use App\Entity\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chat[]    findAll()
 * @method Chat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function getMessages($booking, $booker, $author)
    {
        $query = $this
            ->createQueryBuilder('c')
            ->andWhere('c.booking = :booking')
            ->setParameter('booking', $booking)
            ->andWhere('c.Author = :booker OR c.Author = :author')
            ->setParameter('booker', $booker)
            ->setParameter('author', $author)
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
        return $query;
    }


    public function getNotif($user)
    {
        $query = $this
            ->createQueryBuilder('c')
            ->andWhere('c.seen = :seen')
            ->setParameter('seen', false)
            ->andWhere('c.sendTo = :sendTo')
            ->setParameter('sendTo', $user)
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
        return $query;
    }

    // /**
    //  * @return Chat[] Returns an array of Chat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
