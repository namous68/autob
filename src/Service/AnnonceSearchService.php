<?php
namespace App\Service;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;

class AnnonceSearchService 
{
    private $annonceRepository;

    public function __construct(AnnonceRepository $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
    }

    public function searchByCriteria($marque, $modele, $prixMin)
    {
        $query = $this->annonceRepository->createQueryBuilder('a')   
             ->leftJoin('a.marque', 'm')
        ->where('m.nom LIKE :marque')
        ->andWhere('a.prix >= :prixMin')
        ->setParameter('marque', "%$marque%")
        ->setParameter('prixMin', $prixMin)
        ->getQuery();

    
        return $query->getResult();
        }
}
