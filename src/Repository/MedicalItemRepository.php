<?php

namespace App\Repository;

use App\Entity\MedicalItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalItem[]    findAll()
 * @method MedicalItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalItem::class);
    }

    // /**
    //  * @return MedicalItem[] Returns an array of MedicalItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MedicalItem
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
