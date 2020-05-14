<?php

namespace App\Repository;

use App\Entity\MedicalStaff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MedicalStaff|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalStaff|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalStaff[]    findAll()
 * @method MedicalStaff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalStaffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalStaff::class);
    }

    // /**
    //  * @return MedicalStaff[] Returns an array of MedicalStaff objects
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
    public function findOneBySomeField($value): ?MedicalStaff
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
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
        $qb = $this->createQueryBuilder('m')
            ->innerJoin('m.user','u')
            ->addSelect('u')
            ->innerJoin('m.hall','h')
            ->addSelect('h')
            ->innerJoin('m.speciality','s')
            ->addSelect('s')
            ->innerJoin('h.departament','d')
            ->addSelect('d')
        ;

        if ($term){
            $qb->andWhere('u.firstName LIKE :term OR u.lastName LIKE :term OR h.name LIKE :term OR d.name LIKE :term OR s.title LIKE :term')
                ->setParameter('term','%'.$term.'%');
        }

        return $qb->orderBy('u.firstName','ASC');
    }

}
