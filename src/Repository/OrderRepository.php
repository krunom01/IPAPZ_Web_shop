<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findByEmail($email)
    {
        return $this->createQueryBuilder('o')
            ->select('o')
            ->where('o.userMail = :email')
            ->orderBy('o.date', 'ASC')
            ->setMaxResults(10)
            ->setParameter(':email', $email)
            ->getQuery()
            ->getResult();
    }

    public function findByName($name)
    {
        return $this->createQueryBuilder('o')
            ->select('o')
            ->innerJoin('o.user', 'user')
            ->where('user.firstName = :name')
            ->orderBy('o.date', 'ASC')
            ->setMaxResults(10)
            ->setParameter(':name', $name)
            ->getQuery()
            ->getResult();
    }

    public function getRange($start, $end)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.date BETWEEN :from AND :to')
            ->setParameter('from', $start)
            ->setParameter('to', $end . ' 23:59:59.993')
            ->setMaxResults(10)
            ->orderBy('e.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
