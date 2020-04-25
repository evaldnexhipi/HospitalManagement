<?php

namespace App\Repository;

use App\Entity\ItemsLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemsLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemsLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemsLocation[]    findAll()
 * @method ItemsLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemsLocation::class);
    }

    // /**
    //  * @return ItemsLocation[] Returns an array of ItemsLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemsLocation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
