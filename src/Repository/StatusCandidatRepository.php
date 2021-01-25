<?php

namespace App\Repository;

use App\Entity\StatusCandidat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatusCandidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusCandidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusCandidat[]    findAll()
 * @method StatusCandidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusCandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusCandidat::class);
    }

    // /**
    //  * @return StatusCandidat[] Returns an array of StatusCandidat objects
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
    public function findOneBySomeField($value): ?StatusCandidat
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
