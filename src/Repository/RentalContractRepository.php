<?php

namespace App\Repository;

use App\Entity\RentalContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RentalContract|null find($id, $lockMode = null, $lockVersion = null)
 * @method RentalContract|null findOneBy(array $criteria, array $orderBy = null)
 * @method RentalContract[]    findAll()
 * @method RentalContract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RentalContract::class);
    }

    // /**
    //  * @return RentalContract[] Returns an array of RentalContract objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneBySomeField($value): ?RentalContract
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.tenant = :val')
            ->andWhere('r.status = 1')
            ->setParameter('val', $value)
            ->orderBy('r.status', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
