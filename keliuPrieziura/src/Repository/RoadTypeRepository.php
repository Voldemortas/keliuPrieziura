<?php

namespace App\Repository;

use App\Entity\RoadType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoadType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoadType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoadType[]    findAll()
 * @method RoadType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoadTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoadType::class);
    }

    // /**
    //  * @return RoadType[] Returns an array of RoadType objects
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
    public function findOneBySomeField($value): ?RoadType
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
