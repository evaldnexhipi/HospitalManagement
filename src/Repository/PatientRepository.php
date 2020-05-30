<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    // /**
    //  * @return Patient[] Returns an array of Patient objects
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
    public function findOneBySomeField($value): ?Patient
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilder(?string $term):QueryBuilder {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.room','r')
            ->addSelect('r');

        if ($term){
            $qb->andWhere('p.name LIKE :term OR p.surname LIKE :term OR p.email LIKE :term OR p.cost LIKE :term OR r.name')
                ->setParameter('term','%'.$term.'%');
        }

        return $qb->orderBy('p.createdAt','DESC');
    }

    public function getPatientsNumberToPrevMonth(){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-1 month'))
            ->setParameter('curr_month',new \DateTime('now'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPatientsNumberTo2PrevMonths(){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-2 month'))
            ->setParameter('curr_month',new \DateTime('-1 month'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPatientsNumber (){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalCost(){
        $qb = $this->createQueryBuilder('r')
            ->select('sum(r.cost)')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalCostPrevMonth(){
        $qb = $this->createQueryBuilder('r')
            ->select('sum(r.cost)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-1 month'))
            ->setParameter('curr_month',new \DateTime('now'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalCostPrev2Months(){
        $qb = $this->createQueryBuilder('r')
            ->select('sum(r.cost)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-2 month'))
            ->setParameter('curr_month',new \DateTime('-1 month'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }



}
