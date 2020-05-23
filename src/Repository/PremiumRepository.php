<?php

namespace App\Repository;

use App\Entity\Filter;
use App\Entity\Premium;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function findBestAds()
    {
        return $this->createQueryBuilder('p')
            ->join('p.ad', 'a')
            ->andWhere('a.blackListed = :blackListed')
            ->setParameter('blackListed', false)
            ->andWhere('a.dateFin > :dateNow')
            ->setParameter('dateNow', new \DateTime())
            ->groupBy('a')
            ->addOrderBy('p.value', 'DESC')
            ->addOrderBy('p.startDate', 'DESC')
            //->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPremium()
    {
        return $this->createQueryBuilder('p')
            ->join('p.ad', 'a')
            ->andWhere('a.blackListed = :blackListed')
            ->setParameter('blackListed', false)
            ->groupBy('a')
            ->andWhere('p.value = :value')
            ->setParameter('value', true)
            ->andWhere('a.dateFin > :dateNow')
            ->setParameter('dateNow', new \DateTime())
            ->orderBy('p.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findFilter(Filter $filter)
    {
        $bol = 0;

        if ($filter->getEndPrice() || $filter->getStartPrice() || $filter->getCity() || $filter->getSubCategory() || $filter->getStartDate() || $filter->getEndDate()) {
            $query = $this
                ->createQueryBuilder('p')
                ->innerJoin('p.ad', 'a')
                ->innerJoin('a.cities', 'c', 'WITH', 'c.id = :cityId');
            if ($filter->getSubCategory()) {
                $query->innerJoin('a.subCategory', 's', 'WITH', 's.title = :subCategoryTitle')
                    ->innerJoin('s.categories', 'cat', 'WITH', 'cat.title = :categoryTitle');
            }
            $query->andWhere('a.blackListed = :blackListed')
                ->setParameter('blackListed', false)
                ->groupBy('a')
                ->addOrderBy('p.value', 'DESC')
                ->addOrderBy('p.startDate', 'DESC');
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
            if ($filter->getStartDate()) {
                $query = $query
                    ->andWhere('a.dateDebut <= :startDate')
                    ->setParameter('startDate', \DateTime::createFromFormat('d/m/Y', $filter->getStartDate()));
            }
            if ($filter->getEndDate()) {
                $query = $query
                    ->andWhere('a.dateFin >= :endDate')
                    ->setParameter('endDate', \DateTime::createFromFormat('d/m/Y', $filter->getEndDate()));
            }

            $query = $query->getQuery();
        }

        if ($bol) {
            return $query->getResult();
        } else {
            $query = $this
                ->createQueryBuilder('p')
                ->join('p.ad', 'a')
                ->andWhere('a.blackListed = :blackListed')
                ->andWhere('a.dateFin > :dateNow')
                ->setParameter('dateNow', new \DateTime())
                ->setParameter('blackListed', false)
                ->groupBy('a')
                ->addOrderBy('p.value', 'DESC')
                ->addOrderBy('p.startDate', 'DESC');
            return $query->getQuery()->getResult();
        }
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
