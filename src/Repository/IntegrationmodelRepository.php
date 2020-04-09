<?php

namespace App\Repository;

use App\Entity\Integrationmodel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Integrationmodel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Integrationmodel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Integrationmodel[]    findAll()
 * @method Integrationmodel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntegrationmodelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Integrationmodel::class);
    }

    // /**
    //  * @return Integrationmodel[] Returns an array of Integrationmodel objects
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
    public function findOneBySomeField($value): ?Integrationmodel
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
