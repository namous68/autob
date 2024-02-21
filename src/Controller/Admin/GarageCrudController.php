<?php

namespace App\Controller\Admin;

use App\Entity\Garage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class GarageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Garage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('numeroTel', 'Numéro de téléphone'),
            TextField::new('adress1', 'Adresse 1'),
            TextField::new('adress2', 'Adresse 2'),
            TextField::new('ville', 'Ville'),
            TextField::new('codePostale', 'Code postal'),
            AssociationField::new('professionnel', 'Professionnel')
        ];
    }


    
}
