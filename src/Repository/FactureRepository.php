<?php

namespace App\Repository;

use App\Entity\Depense;
use App\Entity\Facture;
use App\Entity\Rental;
use App\Pagination\Paginator;
use App\Utils\DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    /**
     * @return Facture[] Returns an array of Depense objects
     */
    public function findByMultiparam($tenant, $datedebut, $datefin)
    {
        $qb = $this->createQueryBuilder('d');
        if ($tenant != null) {
            $qb->andWhere('d.tenant = :type')
                ->setParameter('type', $tenant);
        }
        if ($datedebut != null) {
            $db=DateTime::getDateTime($datedebut);
            $end=DateTime::getDateTime($datefin);
            $qb->andWhere('d.createdAt BETWEEN :begin AND :end')
                ->setParameter('begin', $db)
                ->setParameter('end', $end);
        }
        $qb->orderBy('d.createdAt', 'DESC');

        return $qb->getQuery()->getResult();

    }
    public function sumAllPaid()
    {
        $return =$this->createQueryBuilder('o')
            ->select('sum(o.amount) as total')
            ->getQuery()->getOneOrNullResult() ;
        if ($return== null){
            $return=0.0;
        }else{
            $return=$return["total"];
        }
        return $return;
    }
    public function sumAllRemaning()
    {
        $return =$this->createQueryBuilder('o')
            ->select('sum(o.amountDue) as total')
            ->getQuery()->getOneOrNullResult() ;
        if ($return== null){
            $return=0.0;
        }else{
            $return=$return["total"];
        }
        // return floatval($return);
        return $return;
    }
}
