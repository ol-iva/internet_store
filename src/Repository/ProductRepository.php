<?php

namespace App\Repository;

use App\Entity\CategoryOfProducts;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function find($id, $lockMode = null, $lockVersion =null)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id = :id')
//            ->andWhere('p.dateDeletedAt = 0')
            ->setParameter('id', $id);

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('dateDeletedAt = 0');

        return $qb->getQuery()
            ->getResult();
    }

    public function findAllWithDeleted()
    {
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->getResult();
    }

    public function findAllById(array $ids)
    {
        $qb = $this->createQueryBuilder('p')
//            ->where('p.dateDeletedAt = 0')
            ->setParameter('ids', $ids);

        return $qb->getQuery()
            ->getResult();
    }

    public function findAllProductsInCategory(CategoryOfProducts $category)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(10);
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function search(?string $query, int $firstResult = 0, int $maxResults = 10)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.nameOfProduct LIKE :query')
            ->andWhere('p.dateDeletedAt = 0')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->setParameter('query', '%'.addcslashes($query, '%_').'%');

        return new Paginator($qb);
    }

    public function getProductsPaginated(int $firstResult = 0, int $maxResults = 10)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.dateDeletedAt = 0')
            ->orderBy('p.dateCreatedAt', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults);

        return new Paginator($qb);
    }

    public function countCurrentlySold()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id)');
//            ->where('p.dateDeletedAt = 0');

        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    public function findNewest(int $maxResults)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
//            ->where('p.dateDeletedAt = 0 ')
            ->orderBy('p.dateCreatedAt', 'DESC')
            ->setMaxResults($maxResults);

        return $qb->getQuery()
            ->getResult();

    }




    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
