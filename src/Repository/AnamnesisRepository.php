<?php

namespace App\Repository;

use App\Entity\Anamnesis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Anamnesis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anamnesis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anamnesis[]    findAll()
 * @method Anamnesis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnamnesisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anamnesis::class);
    }

    // /**
    //  * @return Anamnesis[] Returns an array of Anamnesis objects
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
    public function findOneBySomeField($value): ?Anamnesis
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getAnamnesesForStaff($staffId): QueryBuilder{
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.medicalStaff = :staffid')
            ->setParameter('staffid',$staffId)
        ;

        return $qb->orderBy('a.createdAt','DESC');
    }

    public function getAnamnesesForClient($clientId): QueryBuilder{
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.client = :clientid')
            ->setParameter('clientid',$clientId)
        ;

        return $qb->orderBy('a.createdAt','DESC');
    }
}
