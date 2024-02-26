<?php

namespace App\Controller\Admin;

use App\Entity\Garage;
use App\Repository\GarageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class GarageCrudController extends AbstractCrudController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Garage::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = $this->entityManager->getRepository(Garage::class)->createQueryBuilder('g');

        // Récupérer l'utilisateur connecté
        /**
         * @var User $user
         */
        $user = $this->getUser();

        // Si l'utilisateur est un professionnel, filtrer les garages en fonction de son ID
        if ($user and $this->isGranted('ROLE_PROFESSIONAL')) {
            $qb->andWhere('g.professionnel = :professionnel')
                ->setParameter('professionnel', $user->getProfessionnel());
        }

        return $qb;
    }

    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id')->hideOnForm(),
    //         TextField::new('nom', 'Nom'),
    //         TextField::new('numeroTel', 'Numéro de téléphone'),
    //         TextField::new('adress1', 'Adresse 1'),
    //         TextField::new('adress2', 'Adresse 2'),
    //         TextField::new('ville', 'Ville'),
    //         TextField::new('codePostale', 'Code postal'),
    //         AssociationField::new('professionnel', 'Professionnel')
    //     ];
    // }

    public function createEntity(string $entityFqcn)
    {
        $garage = new Garage();
        $garage->setProfessionnel($this->getUser()->getProfessionnel());

        return $garage;
    }

    public function configureFields(string $pageName): iterable
    {
        // Initialisation des champs
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('numeroTel', 'Numéro de téléphone'),
            TextField::new('adress1', 'Adresse 1'),
            TextField::new('adress2', 'Adresse 2'),
            TextField::new('ville', 'Ville'),
            TextField::new('codePostale', 'Code postal'),
        ];

        // Récupération de l'utilisateur connecté
        /**
         * @var User $user
         */
        $user = $this->getUser();

        // Vérification du rôle de l'utilisateur
        if ($user) {
            $roles = $user->getRoles();

            // Si l'utilisateur est un professionnel
            if (in_array('ROLE_PROFESSIONNAL', $roles, true)) {
                // Pré-remplir avec le professionnel de l'utilisateur connecté
                $fields[] = AssociationField::new('professionnel', 'Professionnel')
                    ->hideOnIndex() // Ne pas afficher sur la liste
                    ->hideOnDetail() // Ne pas afficher sur la vue détaillée
                    ->hideOnForm() // Ne pas afficher sur le formulaire
                    ->setFormTypeOption('disabled', true); // Désactiver le champ pour qu'il ne soit pas modifiable
            }
            // Si l'utilisateur est un administrateur
            else if (in_array('ROLE_ADMIN', $roles, true)) {
                // Ajout du champ "professionnel" pour permettre à l'administrateur de choisir un professionnel
                $fields[] = AssociationField::new('professionnel', 'Professionnel');
            }
        }

        return $fields;
    }
}
