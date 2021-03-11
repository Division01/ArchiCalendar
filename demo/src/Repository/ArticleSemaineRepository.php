<?php

namespace App\Repository;

use App\Entity\ArticleSemaine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleSemaine|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleSemaine|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleSemaine[]    findAll()
 * @method ArticleSemaine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleSemaineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleSemaine::class);
    }

    // /**
    //  * @return ArticleSemaine[] Returns an array of ArticleSemaine objects
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
    public function findOneBySomeField($value): ?ArticleSemaine
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
