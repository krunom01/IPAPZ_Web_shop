<?php

namespace App\Repository;

use App\Entity\CountryShipping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CountryShipping|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountryShipping|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountryShipping[]    findAll()
 * @method CountryShipping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryShippingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CountryShipping::class);
    }

    // /**
    //  * @return CountryShipping[] Returns an array of CountryShipping objects
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
    public function findOneBySomeField($value): ?CountryShipping
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
