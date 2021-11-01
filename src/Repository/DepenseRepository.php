<?php

namespace App\Repository;

use App\Entity\Depense;
use App\Entity\DepenseType;
use App\Utils\DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Depense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depense[]    findAll()
 * @method Depense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depense::class);
    }

    /**
     * @return Depense[] Returns an array of Depense objects
     */
    public function findByMultiparam($type, $local, $datedebut, $datefin)
    {
        $qb = $this->createQueryBuilder('d');
        if ($type != null) {
            $qb->andWhere('d.depenseType = :type')
                ->setParameter('type', $type);
        }
        if ($datedebut != null) {
            $db=DateTime::getDateTime($datedebut);
            $end=DateTime::getDateTime($datefin);
            $qb->andWhere('d.dateAchat BETWEEN :begin AND :end')
                ->setParameter('begin', $db)
                ->setParameter('end', $end);
        }
        if ($local != null) {
            $qb->andWhere('d.local = :local')
                ->setParameter('local', $local);
        }

        return $qb->getQuery()->getResult();
        /* return $this->createQueryBuilder('d')
             ->andWhere('d.exampleField = :val')
             ->setParameter('val', $local)
             ->orderBy('d.id', 'ASC')
             ->setMaxResults(10)
             ->getQuery()
             ->getResult()
         ;*/
    }
    public function sumAllPaid(DepenseType $depenseType)
    {
        $return =$this->createQueryBuilder('o')
            ->select('sum(o.amount) as total')
            ->where('o.depenseType = :type')
            ->setParameter('type', $depenseType)
            ->getQuery()->getOneOrNullResult() ;
        if ($return== null){
            $return=0.0;
        }else{
            $return=$return["total"];
        }
        return $return;
    }
}
