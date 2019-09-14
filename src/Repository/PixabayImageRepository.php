<?php

namespace App\Repository;

use App\Entity\PixabayImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PixabayImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PixabayImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PixabayImage[]    findAll()
 * @method PixabayImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PixabayImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PixabayImage::class);
    }

    // /**
    //  * @return PixabayImage[] Returns an array of PixabayImage objects
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
    public function findOneBySomeField($value): ?PixabayImage
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
