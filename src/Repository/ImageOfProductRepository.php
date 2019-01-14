<?php

namespace App\Repository;

use App\Entity\ImageOfProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ImageOfProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageOfProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageOfProduct[]    findAll()
 * @method ImageOfProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageOfProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ImageOfProduct::class);
    }

    // /**
    //  * @return ImageOfProduct[] Returns an array of ImageOfProduct objects
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
    public function findOneBySomeField($value): ?ImageOfProduct
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
