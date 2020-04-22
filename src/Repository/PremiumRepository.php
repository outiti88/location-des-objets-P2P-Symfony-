<?php

namespace App\Repository;

use App\Entity\Premium;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Premium|null find($id, $lockMode = null, $lockVersion = null)
 * @method Premium|null findOneBy(array $criteria, array $orderBy = null)
 * @method Premium[]    findAll()
 * @method Premium[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PremiumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Premium::class);
    }

    // /**
    //  * @return Premium[] Returns an array of Premium objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Premium
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
