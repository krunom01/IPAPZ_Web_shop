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

    public function findCountry($value)
    {
        return $this->createQueryBuilder('s')
            ->select('s.country', 's.shippingPrice')
            ->andWhere('s.country = :query')
            ->setParameter('query', $value)
            ->orderBy('s.country', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
