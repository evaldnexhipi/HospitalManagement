<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
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
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
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
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.client','c')
            ->addSelect('c');

        if ($term){
            $qb->andWhere('s.name LIKE :term OR s.description LIKE :term OR d.name LIKE :term OR d.description LIKE :term')
                ->setParameter('term','%'.$term.'%');
        }

        return $qb->orderBy('s.name','ASC');
    }

    public function getAllSoonReservations():QueryBuilder {
        $qb = $this->createQueryBuilder('r')
        ->andWhere('r.status = :term OR r.status = :term2')
            ->setParameter('term','pritje')
            ->setParameter('term2','paguar')
        ->andWhere('r.day >= current_date()')
        ;

        return $qb->orderBy('r.createdAt','DESC');
    }

    public function getAllSoonReservationsForClient($clientId): QueryBuilder{
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.client = :clientid')
            ->setParameter('clientid',$clientId)
            ->andWhere('r.status = :term OR r.status = :term2')
            ->setParameter('term','pritje')
            ->setParameter('term2','paguar')
            ->andWhere('r.day >= current_date()')
        ;

        return $qb->orderBy('r.createdAt','DESC');
    }

    public function getAllSoonReservationsForStaff($staffId): QueryBuilder{
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.medicalStaff = :staffid')
            ->setParameter('staffid',$staffId)
            ->andWhere('r.status = :term2')
            ->setParameter('term2','paguar')
            ->andWhere('r.day >= current_date()')
        ;

        return $qb->orderBy('r.createdAt','DESC');
    }

    public function getAllDoneReservations():QueryBuilder {
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.status = :term OR r.status = :term2')
            ->setParameter('term','paguar')
            ->setParameter('term2','kryer')
            ->andWhere('r.day < current_date()')
        ;

        return $qb->orderBy('r.createdAt','DESC');
    }

    public function getAllDoneReservationsForClient($clientId):QueryBuilder{
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.client = :clientid')
            ->setParameter('clientid',$clientId)
            ->andWhere('r.status = :term OR r.status = :term2')
            ->setParameter('term','paguar')
            ->setParameter('term2','kryer')
            ->andWhere('r.day < current_date()')
        ;

        return $qb->orderBy('r.createdAt','DESC');
    }

    public function getAllDoneReservationsForStaff($staffId):QueryBuilder{
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.medicalStaff = :staffid')
            ->setParameter('staffid',$staffId)
            ->andWhere('r.status = :term OR r.status = :term2')
            ->setParameter('term','paguar')
            ->setParameter('term2','kryer')
//            ->andWhere('r.day > current_date()')
        ;

        return $qb->orderBy('r.createdAt','DESC');
    }

    public function getReservationsNumberToPrevMonth(){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-1 month'))
            ->setParameter('curr_month',new \DateTime('now'))
            ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getReservationsNumberTo2PrevMonths(){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-2 month'))
            ->setParameter('curr_month',new \DateTime('-1 month'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getReservationsNumber (){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalCost(){
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.service','s')
            ->select('sum(s.cost)')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalCostPrevMonth(){
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.service','s')
            ->select('sum(s.cost)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-1 month'))
            ->setParameter('curr_month',new \DateTime('now'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalCostPrev2Months(){
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.service','s')
            ->select('sum(s.cost)')
            ->andWhere('r.createdAt>:prev_month and r.createdAt<=:curr_month')
            ->setParameter('prev_month',new \DateTime('-2 month'))
            ->setParameter('curr_month',new \DateTime('-1 month'))
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTop5Reservations(){
        $qb = $this->createQueryBuilder('r')
            ->setMaxResults(5)
        ;
        return $qb->getQuery()->getResult();
    }

    public function getTop5ReservationsForClient($clientId){
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.client = :clientId')
            ->setParameter('clientId',$clientId)
            ->setMaxResults(5)
            ;
        return $qb->getQuery()->getResult();
    }

    public function getTop5ReservationsForDoc($medicalId){
        $qb = $this->createQueryBuilder('r')
            ->andWhere('r.medicalStaff = :medicalId')
            ->setParameter('medicalId',$medicalId)
            ->setMaxResults(5)
        ;
        return $qb->getQuery()->getResult();
    }

    public function getTotalCostForUser($clientId){
        $qb = $this->createQueryBuilder('r')
            ->select('sum(s.cost)')
            ->innerJoin('r.service','s')
            ->andWhere('r.client = :clientId')
            ->setParameter('clientId',$clientId)
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalCostForDoc($medicalId){
        $qb = $this->createQueryBuilder('r')
            ->select('sum(s.cost)')
            ->innerJoin('r.service','s')
            ->andWhere('r.medicalStaff = :medicalId')
            ->setParameter('medicalId',$medicalId)
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getReservationsNumberForUser($clientId){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.client)')
            ->andWhere('r.client = :clientId')
            ->setParameter('clientId',$clientId)

            ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getReservationsNumberForDoc($medicalId){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.medicalStaff)')
            ->andWhere('r.medicalStaff = :medicalId')
            ->setParameter('medicalId',$medicalId)

        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getDoneReservationsNumberForUser($clientId){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.client)')
            ->andWhere('r.client = :clientId')
            ->setParameter('clientId',$clientId)
            ->andWhere('r.status = :done')
            ->setParameter('done','kryer')

        ;
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getDoneReservationsNumberForDoc($medicalId){
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.medicalStaff)')
            ->andWhere('r.medicalStaff = :medicalId')
            ->setParameter('medicalId',$medicalId)
            ->andWhere('r.status = :done')
            ->setParameter('done','kryer')

        ;
        return $qb->getQuery()->getSingleScalarResult();
    }
}
