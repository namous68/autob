<?php

namespace App\Repository;

use App\Entity\Garage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Garage>
 *
 * @method Garage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Garage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Garage[]    findAll()
 * @method Garage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GarageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Garage::class);
    }

//    /**
//     * @return Garage[] Returns an array of Garage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Garage
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findAll()
{
    return $this->createQueryBuilder('g')
        ->getQuery()
        ->getResult();
}

/**
     * Retourne les garages associés à un professionnel spécifique.
     *
     * @param int $professionnelId L'ID du professionnel
     * @return array|null Les garages associés au professionnel
     */
    public function findByProfessionnelId(int $professionnelId): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.professionnel = :professionnelId')
            ->setParameter('professionnelId', $professionnelId)
            ->getQuery()
            ->getResult();
    }
}

