<?php

namespace App\Repository;

use App\Entity\AddressOfClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AddressOfClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddressOfClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddressOfClient[]    findAll()
 * @method AddressOfClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressOfClientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AddressOfClient::class);
    }

    public function findCurrentAddressWithType(int $userId, string $type): ?AddressOfClient
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.user =: userId')
            ->andWhere('a.type =: type')
            ->setParameter('user_id', $userId)
            ->setParameter('type', $type)
            ->orderBy('a.date_created_at', 'DESC')
            ->setMaxResults(1);

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return AddressOfClient[] Returns an array of AddressOfClient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AddressOfClient
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
