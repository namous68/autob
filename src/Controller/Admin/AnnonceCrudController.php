<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class AnnonceCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Annonce::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    { $qb = $this->entityManager->getRepository(Annonce::class)->createQueryBuilder('a');

    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Si l'utilisateur est un professionnel
    if ($user && $this->isGranted('ROLE_PROFESSIONAL')) {
        // Vérifier si l'utilisateur a un professionnel associé
        $professionnel = $user->getProfessionnel();
        if ($professionnel) {
            // Récupérer le garage associé au professionnel
            $garage = $professionnel->getGarage();
            if ($garage) {
                // Filtrer les annonces en fonction du garage associé au professionnel
                $qb->andWhere('a.garage = :garage')
                    ->setParameter('garage', $garage);
            }
        }
    }

    return $qb;
    }

    public function configureFields(string $pageName): iterable
    {
        // Champs communs à tous les utilisateurs
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre', 'Titre'),
            AssociationField::new('marque'),
            AssociationField::new('model'),
            DateTimeField::new('datePublication'),
            TextField::new('description'),
            TextField::new('ControleTechnique'),
            TextField::new('PremiereMain'),
            TextField::new('Couleur'),
            TextField::new('NombreDePortes'),
            TextField::new('VolumeDeCoffre'),
            TextField::new('Rechargeable'),
            TextField::new('PuissanceFiscale'),
            TextField::new('Garantie'),
            TextField::new('ref'),
            IntegerField::new('misEnCirculation'),
            IntegerField::new('kilometrage'),
            MoneyField::new('prix')->setCurrency('EUR'),
            AssociationField::new('carburant', 'Carburant'),
            
           
            AssociationField::new('garage'),
            
            BooleanField::new('cameraDeRecul', 'cameraDeRecul'),
            BooleanField::new('gps', 'gps'),
            BooleanField::new('bluetooth', 'bluetooth'),
            BooleanField::new('climatisation', 'climatisation'),

           // Ajoutez ce champ pour l'upload d'images
           CollectionField::new('images')
           ->useEntryCrudForm(ImageCrudController::class)
        ];

        // Si l'utilisateur est un professionnel
        if ($this->isGranted('ROLE_PROFESSIONAL')) {
            $fields[] = AssociationField::new('garage', 'Garage');
            
        }

        // Si l'utilisateur est un administrateur
        if ($this->isGranted('ROLE_ADMIN')) {
           
            $fields[] = AssociationField::new('garage', 'Garage');
            
        }

        return $fields;
    }

    
}