<?php

namespace App\Repository;

use App\Entity\CategoryOfProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryOfProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryOfProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryOfProducts[]    findAll()
 * @method CategoryOfProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryOfProductsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryOfProducts::class);
    }



    // /**
    //  * @return CategoryOfProducts[] Returns an array of CategoryOfProducts objects
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
    public function findOneBySomeField($value): ?CategoryOfProducts
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
