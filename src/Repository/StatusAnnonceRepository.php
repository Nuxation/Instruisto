<?php

namespace App\Repository;

use App\Entity\StatusAnnonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatusAnnonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusAnnonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusAnnonce[]    findAll()
 * @method StatusAnnonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusAnnonce::class);
    }

    // /**
    //  * @return StatusAnnonce[] Returns an array of StatusAnnonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatusAnnonce
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
