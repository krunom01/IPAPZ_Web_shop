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



    public function findByEmail($email)
    {
        return $this->createQueryBuilder('o')
            ->select('o')
            ->where('o.userEmail = :email')
            ->orderBy('o.userEmail', 'ASC')
            ->setMaxResults(10)
            ->setParameter(':email', $email)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByName($name)
    {
        return $this->createQueryBuilder('o')
            ->select('o')
            ->where('o.userEmail = :email')
            ->orderBy('o.userEmail', 'ASC')
            ->setMaxResults(10)
            ->setParameter(':email', $name)
            ->getQuery()
            ->getResult()
            ;
    }


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
