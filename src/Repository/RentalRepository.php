<?php

namespace App\Repository;

use App\Entity\Rental;
use App\Entity\Tenant;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    /**
     * @return Rental[] Returns an array of Rental objects
     */

    public function findByTenantIsactive(Tenant $value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.tenant = :val')
            ->andWhere('r.amountDue >= 0.0')
            ->andWhere('r.active = 1')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Rental[] Returns an array of Rental objects
     */

    public function findByYearAndMonthIsactive($year,$month)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.year = :val')
            ->andWhere('r.month = :month')
            //->andWhere('r.amountDue = 0.0')
            ->andWhere('r.active = 1')
            ->andWhere('r.status IN (:ids)')
            ->setParameter('ids', ['complete', 'advanced'])
            ->setParameter('val', $year)
            ->setParameter('month', $month)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Rental[] Returns an array of Rental objects
     */

    public function findByYearAndMonthIsAll($year,$month)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.year = :val')
            ->andWhere('r.month = :month')
            ->andWhere('r.active = 1')
           // ->andWhere('r.status IN (:ids)')
           // ->setParameter('ids', ['complete', 'advanced'])
            ->setParameter('val', $year)
            ->setParameter('month', $month)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }
    public function getAmountRentalByYearAndMonth($year,$month)
    {
        $lists = $this->findByYearAndMonthIsactive($year,$month);
        $som = 0.0;
        foreach ($lists as $rental) {
            $som += ($rental->getAmount() - $rental->getAmountDue());
        }
        return $som;
    }
    public function getAmountRentalByYearAndMonthAll($year,$month)
    {
        $lists = $this->findByYearAndMonthIsAll($year,$month);
        $som = 0.0;
        foreach ($lists as $rental) {
            $som += $rental->getAmount();
        }
        return $som;
    }
    public function getAmountDue(Tenant $tenant)
    {
        $lists = $this->findByTenantIsactive($tenant);
        $som = 0.0;
        foreach ($lists as $rental) {
            $som += $rental->getAmountDue();
        }
        return $som;
    }

    /*
    public function findOneBySomeField($value): ?Rental
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findLatest(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.createdAt <= :now')
            ->andWhere('p.active = 1')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('now', new \DateTime());

        return (new Paginator($qb))->paginate($page);
    }
    public function findRelance(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.endDate <= :now')
            ->andWhere('p.active = 1')
            ->andWhere('p.amountDue > 0.0')
            ->orderBy('p.tenant', 'DESC')
            ->setParameter('now', new \DateTime());

        return (new Paginator($qb))->paginate($page);
    }
    /**
     * @return Rental[] Returns an array of Depense objects
     */
    public function findByMultiparam($tenant, $year, $month)
    {
        $qb = $this->createQueryBuilder('d');
        if ($tenant != null) {
            $qb->andWhere('d.tenant = :tenat')
                ->setParameter('tenat', $tenant);
        }
        if ($year != null) {
            $qb->andWhere('d.year = :year')
                ->setParameter('year', $year);
        }
        if ($month != null) {
            $qb->andWhere('d.month = :local')
                ->setParameter('local', $month);
        }
        $qb->andWhere('d.active = 1');
        return $qb->getQuery()->getResult();
    }

}
