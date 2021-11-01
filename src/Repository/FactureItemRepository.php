<?php

namespace App\Repository;

use App\Entity\FactureItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FactureItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureItem[]    findAll()
 * @method FactureItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureItem::class);
    }

     /**
     * @return FactureItem[] Returns an array of FactureItem objects
      */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?FactureItem
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
