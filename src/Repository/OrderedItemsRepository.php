<?php

namespace App\Repository;

use App\Entity\OrderedItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderedItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderedItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderedItems[]    findAll()
 * @method OrderedItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderedItemsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderedItems::class);
    }


    //  * @return OrderedItems[] Returns an array of OrderedItems objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderedItems
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
