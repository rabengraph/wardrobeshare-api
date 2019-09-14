<?php

namespace App\Repository;

use App\Entity\Clothing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Clothing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clothing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clothing[]    findAll()
 * @method Clothing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClothingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clothing::class);
    }

    // /**
    //  * @return Clothing[] Returns an array of Clothing objects
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
    public function findOneBySomeField($value): ?Clothing
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
