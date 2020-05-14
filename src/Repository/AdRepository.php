<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\Filter;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * @return Ad[] Returns an array of Ad objects
     */

    public function findBestAds($limit)
    {
        return $this->createQueryBuilder('a')
            ->select('a as annonce, AVG(c.rating) as avgRatings')
            ->join('a.comments', 'c')
            ->groupBy('a')
            ->orderBy('avgRatings', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }






    /**
     * @return Ad[] Returns an array of Ad objects
     */


    public function findFilter(Filter $filter)
    {
        $bol = 0;


        if ($filter->getEndPrice() || $filter->getStartPrice() || $filter->getCity()) {
            $query = $this
                ->createQueryBuilder('a')
                ->innerJoin('a.cities', 'c', 'WITH', 'c.id = :cityId');
            if ($filter->getSubCategory()) {
                $query->innerJoin('a.subCategory', 's', 'WITH', 's.title = :subCategoryTitle')
                    ->innerJoin('s.categories', 'cat', 'WITH', 'cat.title = :categoryTitle');
            }
            $query->andWhere('a.blackListed = :blackListed')
                ->setParameter('blackListed', false);
            $bol = 1;
            if ($filter->getEndPrice()) {
                $query = $query
                    ->andWhere('a.price <= :endPrice')
                    ->setParameter('endPrice', $filter->getEndPrice());
            }

            if ($filter->getStartPrice()) {
                $query = $query
                    ->andWhere('a.price >= :startPrice')
                    ->setParameter('startPrice', $filter->getStartPrice());
            }

            if ($filter->getCity()) {
                $query = $query->setParameter(':cityId', $filter->getCity()->getId());
            }

            if ($filter->getSubCategory() != null) {
                $query = $query->setParameter(':subCategoryTitle', $filter->getSubCategory());
                if ($filter->getCategory()) {
                    $query = $query->setParameter(':categoryTitle', $filter->getCategory());
                }
            }

            $query = $query->getQuery();
        }




        if ($bol) {
            return $query->getResult();
        } else {
            $query = $this
                ->createQueryBuilder('a')
                ->andWhere('a.blackListed = :blackListed')
                ->setParameter('blackListed', false);
            return $query->getQuery()->getResult();
        }
    }





    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
