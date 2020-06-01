<?php

namespace App\Repository;

use App\Entity\Results;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Results|null find($id, $lockMode = null, $lockVersion = null)
 * @method Results|null findOneBy(array $criteria, array $orderBy = null)
 * @method Results[]    findAll()
 * @method Results[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Results::class);
    }

    // /**
    //  * @return Results[] Returns an array of Results objects
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

    /*
    public function findOneBySomeField($value): ?Results
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getResultsForClient($clientId): QueryBuilder{
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.client = :clientid')
            ->setParameter('clientid',$clientId)
        ;

        return $qb->orderBy('r.createdAt','DESC');
    }

    public function getResultsNumberForUser($clientId){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.client)')
            ->andWhere('r.client = :clientId')
            ->setParameter('clientId',$clientId)
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getResultsNumberForDoc($medicalId){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.medicalStaff)')
            ->andWhere('r.medicalStaff = :medicalId')
            ->setParameter('medicalId',$medicalId)
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getLastResultForClient($clientId)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.client = :clientid')
            ->setParameter('clientid',$clientId)
            ->orderBy('r.createdAt','DESC')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getResult();
    }

    public function getLastResultForDoc($medicalId)
    {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.medicalStaff = :medicalId')
            ->setParameter('medicalId',$medicalId)
            ->orderBy('r.createdAt','DESC')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getResult();
    }
}
