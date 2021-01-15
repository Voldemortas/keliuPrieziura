<?php

namespace App\Repository;

use App\Entity\Cipher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cipher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cipher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cipher[]    findAll()
 * @method Cipher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CipherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cipher::class);
    }

    // /**
    //  * @return Cipher[] Returns an array of Cipher objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cipher
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
