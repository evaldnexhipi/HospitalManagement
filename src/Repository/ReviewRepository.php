<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
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
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getWithSearchQueryBuilder():QueryBuilder {
        $qb = $this->createQueryBuilder('p');

        return $qb->orderBy('p.createdAt','DESC');
    }

    public function getReviewsNumberToPrevMonth(){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-1 month'))
            ->setParameter('curr_month',new \DateTime('now'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getReviewsNumberTo2PrevMonths(){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-2 month'))
            ->setParameter('curr_month',new \DateTime('-1 month'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getReviewsNumber (){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTop3Reviews(){
        $qb = $this->createQueryBuilder('r')
            ->setMaxResults(3);

        return $qb->orderBy('r.createdAt','ASC')->getQuery()->getResult();
    }

    public function getReviewsNumberForUser($clientId){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.client)')
            ->andWhere('r.client = :clientId')
            ->setParameter('clientId',$clientId)

        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTop4Reviews(){
        $qb = $this->createQueryBuilder('r')
            ->setMaxResults(4);

        return $qb->getQuery()->getResult();
    }

}
