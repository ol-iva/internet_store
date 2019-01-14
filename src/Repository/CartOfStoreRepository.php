<?php

namespace App\Repository;

use App\Entity\CartOfStore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CartOfStore|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartOfStore|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartOfStore[]    findAll()
 * @method CartOfStore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartOfStoreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CartOfStore::class);
    }

    // /**
    //  * @return CartOfStore[] Returns an array of CartOfStore objects
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
    public function findOneBySomeField($value): ?CartOfStore
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
