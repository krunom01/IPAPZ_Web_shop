<?php

namespace App\Repository;

use App\Entity\Shopcard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Shopcard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shopcard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shopcard[]    findAll()
 * @method Shopcard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopcardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Shopcard::class);
    }

    // /**
    //  * @return Shopcard[] Returns an array of Shopcard objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shopcard
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
