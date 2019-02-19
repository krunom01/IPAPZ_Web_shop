<?php

namespace App\Repository;

use App\Entity\ImagesOfProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ImagesOfProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesOfProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesOfProduct[]    findAll()
 * @method ImagesOfProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesOfProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ImagesOfProduct::class);
    }

    // /**
    //  * @return ImagesOfProduct[] Returns an array of ImagesOfProduct objects
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
    public function findOneBySomeField($value): ?ImagesOfProduct
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
