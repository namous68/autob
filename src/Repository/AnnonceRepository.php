<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    /**
     * @return Annonce[] Returns an array of Annonce objects
     */
    public function findByExampleField(): array
    {
        return $this->createQueryBuilder('a')
          //  ->andWhere('a.exampleField = :val')
          //  ->setParameter('val', $value)
          // ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Annonce
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function searchByCriteria($marque, $modele, $prixMin)
    {
        $query = $this->createQueryBuilder('a')
        ->leftJoin('a.marque', 'm')
        ->where('m.nom LIKE :marque')
        ->andWhere('a.prix >= :prixMin')
        ->setParameter('marque', "%$marque%")
        ->setParameter('prixMin', $prixMin)
        ->getQuery();

        return $query->getResult();
    }

    public function getPaginatedAnnonces($page, $limit)
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.datePublication', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }


     /**
     * @param string $nom
     * @return Annonce[]
     */
    public function findByGarageNom(string $nom): array
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.garage', 'g') // Supposons que la relation avec Garage s'appelle "garage"
            ->andWhere('g.nom = :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();
    }
}
