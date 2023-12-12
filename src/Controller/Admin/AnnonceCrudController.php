<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class AnnonceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annonce::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            AssociationField::new('marque'),
            AssociationField::new('model'),
            DateTimeField::new('datePublication'),
            TextField::new('description'),
            TextField::new('ref'),
            IntegerField::new('misEnCirculation'),
            IntegerField::new('kilometrage'),
            MoneyField::new('prix')->setCurrency('EUR'),
            AssociationField::new('carburant', 'Carburant'),
            
           
            AssociationField::new('garage'),

           // Ajoutez ce champ pour l'upload d'images
           ImageField::new('imageFile')
           ->setBasePath('src/assets/js/images/uploads/images/') // Chemin vers le dossier où les images seront stockées
           ->setUploadDir('src/assets/js/images/uploads/images/') // Chemin vers le dossier web où les images seront téléchargées
           ->setUploadedFileNamePattern('[randomhash].[extension]')
           ->setLabel('Image'), // Étiquette du champ
        ];
    }
    
}
